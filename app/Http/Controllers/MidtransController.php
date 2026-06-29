<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    /**
     * Endpoint webhook Midtrans (tidak butuh auth, tapi diverifikasi via signature key).
     * Midtrans akan POST ke sini setiap kali status pembayaran berubah.
     */
    public function notification(Request $request)
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new \Midtrans\Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $orderId           = $notification->order_id;         // BKG-{pesanan_id}-{timestamp}
        $transactionStatus = $notification->transaction_status;
        $paymentType       = $notification->payment_type;
        $fraudStatus       = $notification->fraud_status ?? 'accept';

        // Cari record pembayaran berdasarkan midtrans_order_id
        $pembayaran = Pembayaran::where('midtrans_order_id', $orderId)->first();

        if (!$pembayaran) {
            Log::warning("Midtrans: pembayaran tidak ditemukan untuk order_id: {$orderId}");
            return response()->json(['message' => 'Order not found'], 404);
        }

        $pesanan = $pembayaran->pesanan;

        // Update channel pembayaran
        $pembayaran->update(['payment_channel' => $paymentType]);

        // Mapping status Midtrans → status internal
        if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
            $this->handlePembayaranBerhasil($pembayaran, $pesanan);
        } elseif ($transactionStatus === 'settlement') {
            $this->handlePembayaranBerhasil($pembayaran, $pesanan);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $pembayaran->update(['status' => 'Gagal']);
        } elseif ($transactionStatus === 'pending') {
            $pembayaran->update(['status' => 'Menunggu Verifikasi']);
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Handle pembayaran berhasil: update status pembayaran & pesanan.
     */
    private function handlePembayaranBerhasil(Pembayaran $pembayaran, Pesanan $pesanan): void
    {
        // Jangan proses ulang jika sudah Valid
        if ($pembayaran->status === 'Valid') {
            return;
        }

        $pembayaran->update(['status' => 'Valid']);

        $jenis = $pembayaran->jenis_pembayaran;

        if ($jenis === 'DP') {
            $pesanan->update(['status' => 'DP Lunas', 'tipe_pembayaran' => 'DP']);
        } elseif ($jenis === 'Pelunasan') {
            $pesanan->update(['status' => 'Lunas', 'tipe_pembayaran' => 'Lunas']);
        } else {
            // Lunas penuh sekaligus
            $pesanan->update(['status' => 'Lunas', 'tipe_pembayaran' => 'Lunas']);
        }

        // Kirim email konfirmasi otomatis
        try {
            $pesanan->load(['user', 'paketWisata', 'armadas.jeep', 'armadas.supir', 'komunitas', 'pembayaran']);
            if ($pesanan->status === 'DP Lunas') {
                \Illuminate\Support\Facades\Mail::to($pesanan->user->email)->send(new \App\Mail\PaymentConfirmedMail($pesanan));
            } elseif ($pesanan->status === 'Lunas') {
                \Illuminate\Support\Facades\Mail::to($pesanan->user->email)->send(new \App\Mail\PaymentConfirmedMail($pesanan));
                \Illuminate\Support\Facades\Mail::to($pesanan->user->email)->send(new \App\Mail\EtiketLunasMail($pesanan));
            }
        } catch (\Exception $e) {
            Log::error("Midtrans Notification Email Error: " . $e->getMessage());
        }

        Log::info("Midtrans: Pembayaran {$pembayaran->id} berhasil. Pesanan {$pesanan->id} → status: {$pesanan->status}");
    }

    /**
     * Halaman sukses setelah user selesai di Snap Midtrans.
     * Midtrans akan redirect ke sini setelah user menyelesaikan pembayaran.
     * Catatan: redirect ini hanya UI, konfirmasi asli tetap dari webhook /notification.
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;

        if ($orderId) {
            $pembayaran = Pembayaran::where('midtrans_order_id', $orderId)
                ->with('pesanan')
                ->first();

            if ($pembayaran && $pembayaran->pesanan) {
                return redirect()->route('booking.show', $pembayaran->pesanan->id)
                    ->with('success', '✅ Pembayaran berhasil diproses! Status pesanan Anda akan diperbarui secara otomatis.');
            }
        }

        return redirect()->route('dashboard')
            ->with('success', '✅ Pembayaran berhasil diproses! Mohon tunggu konfirmasi dari sistem kami.');
    }

    /**
     * Halaman jika user menutup Snap sebelum selesai.
     */
    public function unfinish(Request $request)
    {
        return redirect()->route('dashboard')
            ->with('error', 'Pembayaran belum diselesaikan. Anda dapat melanjutkan pembayaran kapan saja.');
    }

    /**
     * Halaman jika pembayaran ditolak/error.
     */
    public function error(Request $request)
    {
        return redirect()->route('dashboard')
            ->with('error', 'Pembayaran gagal atau ditolak. Silakan coba lagi.');
    }
}
