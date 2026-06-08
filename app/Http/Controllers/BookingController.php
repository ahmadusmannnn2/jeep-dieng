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
        $request->validate([
            'tanggal_jadwal'    => 'required|date|after_or_equal:today',
            'waktu_jemput'      => 'required',
            'titik_jemput'      => 'required|string|max:255',
            'jumlah_pengunjung' => 'required|integer|min:1|max:6',
            'catatan'           => 'nullable|string'
        ]);

        $catatanAkhir = "Jam Jemput: " . $request->waktu_jemput . "\n" . "Catatan Tambahan: " . $request->catatan;

        $pesanan = Pesanan::create([
            'user_id'           => Auth::id(),
            'paket_wisata_id'   => $paketWisata->id,
            'komunitas_id'      => $paketWisata->komunitas_id,
            'tanggal_jadwal'    => $request->tanggal_jadwal,
            'titik_jemput'      => $request->titik_jemput,
            'jumlah_pengunjung' => $request->jumlah_pengunjung,
            'catatan'           => $catatanAkhir,
            'total_harga'       => $paketWisata->harga,
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

    // 3. Menampilkan Form Upload Pembayaran
    public function payment(Pesanan $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak dapat melihat tagihan orang lain.');
        }

        if (in_array($pesanan->status, ['Lunas', 'Selesai', 'Dibatalkan', 'Disetujui'])) {
            return redirect()->route('dashboard')->with('error', 'Pesanan ini sudah diproses atau dibatalkan.');
        }

        $pesanan->load('paketWisata');
        return view('frontend.booking.payment', compact('pesanan'));
    }

    // 4. Proses Simpan Bukti Bayar
    public function paymentStore(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'bukti_pembayaran'  => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'metode_pembayaran' => 'required|string',
            'jenis_pembayaran'  => 'required|in:DP,Pelunasan,Lunas',
        ]);

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $jumlahBayar = $pesanan->total_harga;
        if ($request->jenis_pembayaran === 'DP') {
            $jumlahBayar = $pesanan->total_harga / 2;
            $pesanan->update(['tipe_pembayaran' => 'DP']);
        } elseif ($request->jenis_pembayaran === 'Pelunasan') {
            $jumlahBayar = $pesanan->total_harga / 2;
        } else {
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

        if ($pesanan->status === 'DP Lunas') {
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
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $pesanan->load(['paketWisata', 'jadwal', 'jeep', 'supir', 'pembayaran', 'komunitas']);
        return view('frontend.booking.show', compact('pesanan'));
    }

    // 6. Mencetak E-Tiket Customer
    public function printTicket(Pesanan $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Ini bukan tiket Anda.');
        }

        $pesanan->load(['paketWisata', 'jadwal', 'jeep', 'supir', 'pembayaran', 'komunitas']);
        return view('frontend.booking.print', compact('pesanan'));
    }
}