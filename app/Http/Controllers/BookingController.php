<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaketWisata;
use App\Models\JadwalKeberangkatan;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmationMail;
use App\Mail\PaymentUploadedMail;

class BookingController extends Controller
{
    // 1. Menampilkan Form Booking
    public function create(PaketWisata $paketWisata)
    {
        $jadwal = JadwalKeberangkatan::where('komunitas_id', $paketWisata->komunitas_id)
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get();

        return view('frontend.booking.create', compact('paketWisata', 'jadwal'));
    }

    // 2. Memproses Data Booking
    public function store(Request $request, PaketWisata $paketWisata)
    {
        // Validasi max:6 dihapus untuk rombongan besar, ditambah tipe_trip
        $request->validate([
            'tipe_trip'         => 'required|in:Private,Group',
            'tanggal_jadwal'    => 'required|date|after_or_equal:today',
            'waktu_jemput'      => 'required',
            'titik_jemput'      => 'required|string|max:255',
            'jumlah_pengunjung' => 'required|integer|min:1',
            'catatan'           => 'nullable|string'
        ]);

        // Kalkulasi Backend: 1 Jeep = 4 Orang (pembulatan ke atas)
        $jumlahPengunjung = $request->jumlah_pengunjung;
        $jumlahJeep = ceil($jumlahPengunjung / 4);
        $totalHarga = $jumlahJeep * $paketWisata->harga;

        $catatanAkhir = "Jam Jemput: " . $request->waktu_jemput . "\n" . "Catatan Tambahan: " . $request->catatan;

        $pesanan = Pesanan::create([
            'user_id'           => Auth::id(),
            'paket_wisata_id'   => $paketWisata->id,
            'komunitas_id'      => $paketWisata->komunitas_id,
            'tanggal_jadwal'    => $request->tanggal_jadwal,
            'tipe_trip'         => $request->tipe_trip,
            'titik_jemput'      => $request->titik_jemput,
            'jumlah_pengunjung' => $jumlahPengunjung,
            'jumlah_jeep'       => $jumlahJeep,
            'catatan'           => $catatanAkhir,
            'total_harga'       => $totalHarga,
            'status'            => 'Pending',
        ]);

        // 📧 Kirim email konfirmasi pemesanan ke customer
        $pesanan->load(['user', 'paketWisata', 'komunitas']);
        try {
            Mail::to($pesanan->user->email)->send(new BookingConfirmationMail($pesanan));
        } catch (\Exception $e) {
            // Jangan hentikan proses jika email gagal terkirim
        }

        return redirect()->route('dashboard')->with('booking_success', true);
    }

    // 3. Menampilkan Form Upload Pembayaran + Midtrans Snap
    public function payment(Pesanan $pesanan)
    {
        if ((int) $pesanan->user_id !== (int) Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak dapat melihat tagihan orang lain.');
        }

        if (in_array($pesanan->status, ['Lunas', 'Selesai', 'Dibatalkan'])) {
            return redirect()->route('dashboard')->with('error', 'Pesanan ini sudah lunas, selesai, atau dibatalkan.');
        }

        $pesanan->load(['paketWisata', 'user']);

        // Tentukan jenis & jumlah bayar (Hanya ada DP 50% dan Pelunasan 50%)
        $jenisPembayaran = in_array($pesanan->status, ['DP Lunas', 'Selesai Perjalanan']) ? 'Pelunasan' : 'DP';
        $jumlahBayar     = (int) ($pesanan->total_harga / 2);

        // Order ID unik per sesi pembayaran (DP atau Pelunasan)
        $existingPembayaran = Pembayaran::where('pesanan_id', $pesanan->id)
            ->where('jenis_pembayaran', $jenisPembayaran)
            ->where('status', 'Menunggu Verifikasi')
            ->whereNotNull('midtrans_order_id')
            ->latest()
            ->first();

        if ($existingPembayaran && $existingPembayaran->snap_token) {
            // Reuse token yang masih valid
            $orderId   = $existingPembayaran->midtrans_order_id;
            $snapToken = $existingPembayaran->snap_token;
            $pembayaran = $existingPembayaran;
        } else {
            // Buat order_id baru
            $orderId = 'BKG-' . $pesanan->id . '-' . strtolower($jenisPembayaran) . '-' . time();

            // Buat record Pembayaran di DB SEBELUM hit Midtrans API
            $pembayaran = Pembayaran::create([
                'pesanan_id'         => $pesanan->id,
                'jenis_pembayaran'   => $jenisPembayaran,
                'metode_pembayaran'  => 'Midtrans',
                'jumlah_bayar'       => $jumlahBayar,
                'midtrans_order_id'  => $orderId,
                'status'             => 'Menunggu Verifikasi',
            ]);

            // Generate Midtrans Snap Token
            \Midtrans\Config::$serverKey    = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');

            $snapToken = null;

            try {
                $params = [
                    'transaction_details' => [
                        'order_id'     => $orderId,
                        'gross_amount' => $jumlahBayar,
                    ],
                    'customer_details' => [
                        'first_name' => $pesanan->user->name ?? 'Customer',
                        'email'      => $pesanan->user->email ?? '',
                    ],
                    'item_details' => [
                        [
                            'id'       => 'PKT-' . $pesanan->paket_wisata_id . '-' . strtolower($jenisPembayaran),
                            'price'    => $jumlahBayar,
                            'quantity' => 1,
                            'name'     => substr(($pesanan->paketWisata->nama_paket ?? 'Paket Wisata') . ' (' . $jenisPembayaran . ')', 0, 50),
                        ],
                    ],
                    'callbacks' => [
                        'finish'   => route('midtrans.finish'),
                        'unfinish' => route('midtrans.unfinish'),
                        'error'    => route('midtrans.error'),
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                // Simpan snap token ke DB agar bisa di-reuse
                $pembayaran->update(['snap_token' => $snapToken]);

            } catch (\Exception $e) {
                // Jika Midtrans gagal, hapus record
                $pembayaran->delete();
                \Illuminate\Support\Facades\Log::error('Midtrans snap token error: ' . $e->getMessage());
            }
        }

        return view('frontend.booking.payment', compact('pesanan', 'snapToken', 'jenisPembayaran', 'jumlahBayar'));
    }

    // 4. Proses Simpan Bukti Bayar
    public function paymentStore(Request $request, Pesanan $pesanan)
    {
        if ((int) $pesanan->user_id !== (int) Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'bukti_pembayaran'  => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'metode_pembayaran' => 'required|string',
            'jenis_pembayaran'  => 'required|in:DP,Pelunasan,Lunas',
        ]);

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // Hitung jumlah bayar berdasarkan jenis pembayaran
        if ($request->jenis_pembayaran === 'DP') {
            // DP = 50% dari total harga
            $jumlahBayar = $pesanan->total_harga / 2;
            $pesanan->update(['tipe_pembayaran' => 'DP']);
        } elseif ($request->jenis_pembayaran === 'Pelunasan') {
            // Pelunasan = sisa 50% yang belum dibayar
            $jumlahBayar = $pesanan->total_harga / 2;
            $pesanan->update(['tipe_pembayaran' => 'Lunas']);
        } else {
            // Lunas sekaligus = 100% total harga
            $jumlahBayar = $pesanan->total_harga;
            $pesanan->update(['tipe_pembayaran' => 'Lunas']);
        }

        Pembayaran::create([
            'pesanan_id'        => $pesanan->id,
            'jumlah_bayar'      => $jumlahBayar,
            'bukti_bayar'       => $path,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jenis_pembayaran'  => $request->jenis_pembayaran,
            'status'            => 'Menunggu Verifikasi',
        ]);

        // Jika pesanan sudah Selesai Perjalanan dan customer upload pelunasan,
        // ubah status menjadi Pending agar admin bisa verifikasi ulang.
        // Jika pesanan masih Pending biasa (upload DP pertama kali), biarkan statusnya.
        if ($pesanan->status === 'Selesai Perjalanan' && $request->jenis_pembayaran === 'Pelunasan') {
            $pesanan->update(['status' => 'Pending']);
        }

        // 📧 Kirim email notifikasi bukti pembayaran diterima
        $pesanan->load(['user', 'paketWisata', 'pembayaran']);
        try {
            Mail::to($pesanan->user->email)->send(new PaymentUploadedMail($pesanan));
        } catch (\Exception $e) {
            // Jangan hentikan proses jika email gagal terkirim
        }

        return redirect()->route('dashboard')->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu konfirmasi dari Admin kami.');
    }

    // 5. Menampilkan Detail Riwayat Pesanan / E-Tiket Customer
    public function show(Pesanan $pesanan)
    {
        if ((int) $pesanan->user_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        // Load semua relasi termasuk pembayarans (plural) untuk riwayat pembayaran
        $pesanan->load(['paketWisata', 'jadwal', 'armadas.jeep', 'armadas.supir', 'pembayaran', 'pembayarans', 'komunitas']);
        return view('frontend.booking.show', compact('pesanan'));
    }

    // 6. Mencetak E-Tiket Customer
    public function printTicket(Pesanan $pesanan)
    {
        if ((int) $pesanan->user_id !== (int) Auth::id()) {
            abort(403, 'Akses ditolak. Ini bukan tiket Anda.');
        }

        $pesanan->load(['paketWisata', 'jadwal', 'armadas.jeep', 'armadas.supir', 'pembayaran', 'pembayarans', 'komunitas']);
        return view('frontend.booking.print', compact('pesanan'));
    }

    // 7. Menghapus Pesanan oleh Customer
    public function destroy(Pesanan $pesanan)
    {
        if ((int) $pesanan->user_id !== (int) Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Jangan izinkan penghapusan jika pesanan sudah dibayar (DP/Lunas) atau selesai
        if (in_array($pesanan->status, ['DP Lunas', 'Lunas', 'Selesai'])) {
            return back()->with('error', 'Pesanan yang sudah terbayar atau selesai tidak dapat dibatalkan/dihapus.');
        }

        $pesanan->delete();
        return redirect()->route('dashboard')->with('success', 'Pesanan Anda berhasil dihapus.');
    }

    // 8. Menyimpan Ulasan / Testimoni dari Customer
    public function storeTestimoni(Request $request, Pesanan $pesanan)
    {
        if ((int) $pesanan->user_id !== (int) Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if (!in_array($pesanan->status, ['Lunas', 'Selesai'])) {
            return back()->with('error', 'Anda hanya bisa memberikan ulasan setelah pesanan Lunas atau Selesai.');
        }

        // Pastikan pesanan belum diulas
        if ($pesanan->testimoni()->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'pesan' => 'required|string|max:1000',
            'asal_kota' => 'nullable|string|max:100',
        ]);

        \App\Models\Testimoni::create([
            'user_id' => Auth::id(),
            'pesanan_id' => $pesanan->id,
            'rating' => $request->rating,
            'pesan' => $request->pesan,
            'asal_kota' => $request->asal_kota,
            'nama' => Auth::user()->name,
            'is_tampil' => true, // Sesuai kesepakatan, langsung tampil di landing page
        ]);

        return redirect()->route('dashboard')->with('success', 'Terima kasih! Ulasan Anda berhasil disimpan dan ditayangkan.');
    }
}