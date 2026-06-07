<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaketWisata;
use App\Models\JadwalKeberangkatan;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // 1. Menampilkan Form Booking
    public function create(PaketWisata $paketWisata)
    {
        // Cari jadwal yang tersedia sesuai komunitas paket ini, dan tanggalnya belum lewat
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
        // 1. Validasi input, tambahkan jumlah_pengunjung (Maksimal 6)
        $request->validate([
            'tanggal_jadwal' => 'required|date|after_or_equal:today',
            'waktu_jemput' => 'required',
            'titik_jemput' => 'required|string|max:255',
            'jumlah_pengunjung' => 'required|integer|min:1|max:6',
            'catatan' => 'nullable|string'
        ]);

        // 2. Gabungkan catatan dan jam jemput untuk mempermudah admin
        $catatanAkhir = "Jam Jemput: " . $request->waktu_jemput . "\n" . "Catatan Tambahan: " . $request->catatan;

        // 3. Simpan ke Database
        Pesanan::create([
            'user_id'           => Auth::id(),
            'paket_wisata_id'   => $paketWisata->id,
            'komunitas_id'      => $paketWisata->komunitas_id,
            'tanggal_jadwal'    => $request->tanggal_jadwal,
            'titik_jemput'      => $request->titik_jemput,
            'jumlah_pengunjung' => $request->jumlah_pengunjung,
            'catatan'           => $catatanAkhir,
            // HARGA PER JEEP: Karena pesanan maksimal 6 orang (1 jeep), harganya flat per paket/jeep.
            'total_harga'       => $paketWisata->harga,
            'status'            => 'Pending',
        ]);

        // 4. Arahkan ke Riwayat dengan animasi sukses
        return redirect()->route('dashboard')->with('booking_success', true);
    }

    // 3. Menampilkan Form Upload Pembayaran
    // HALAMAN UPLOAD BUKTI BAYAR
    public function payment(Pesanan $pesanan)
    {
        // 1. Keamanan: Pastikan hanya pemilik pesanan yang bisa mengakses halamannya
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak dapat melihat tagihan orang lain.');
        }

        // 2. Cegah akses jika pesanan sudah dibayar lunas atau dibatalkan
        if (in_array($pesanan->status, ['Lunas', 'Selesai', 'Dibatalkan', 'Disetujui'])) {
            return redirect()->route('dashboard')->with('error', 'Pesanan ini sudah diproses atau dibatalkan.');
        }

        // Muat relasi paket agar bisa ditampilkan di ringkasan
        $pesanan->load('paketWisata');

        return view('frontend.booking.payment', compact('pesanan'));
    }

    // PROSES SIMPAN BUKTI BAYAR
    // PROSES SIMPAN BUKTI BAYAR
    public function paymentStore(Request $request, Pesanan $pesanan)
    {
        // 1. Keamanan
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }

        // 2. Validasi file gambar yang diunggah
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'metode_pembayaran' => 'required|string',
            'jenis_pembayaran' => 'required|in:DP,Pelunasan,Lunas',
        ]);

        // 3. Simpan gambar ke folder storage/app/public/bukti_pembayaran
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // Logic for DP vs Lunas
        $jumlahBayar = $pesanan->total_harga;
        if ($request->jenis_pembayaran === 'DP') {
            $jumlahBayar = $pesanan->total_harga / 2;
            $pesanan->update(['tipe_pembayaran' => 'DP']);
        } elseif ($request->jenis_pembayaran === 'Pelunasan') {
            $jumlahBayar = $pesanan->total_harga / 2;
        } else {
            $pesanan->update(['tipe_pembayaran' => 'Lunas']);
        }

        // 4. Masukkan ke tabel pembayaran
        Pembayaran::create([
            'pesanan_id'        => $pesanan->id,
            'jumlah_bayar'      => $jumlahBayar,
            'bukti_bayar'       => $path,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jenis_pembayaran'  => $request->jenis_pembayaran,
            'status'            => 'Menunggu Verifikasi',
        ]);

        // Ubah status pesanan menjadi Pending jika sedang DP Lunas tapi belum diverifikasi pelunasannya
        if ($pesanan->status === 'DP Lunas') {
            $pesanan->update(['status' => 'Pending']);
        }

        return redirect()->route('dashboard')->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu konfirmasi dari Admin kami.');
    }

    // 5. TAMBAHAN UTUH: Menampilkan Halaman Detail Riwayat Pesanan / E-Tiket Customer
    public function show(Pesanan $pesanan)
    {
        // Keamanan: Pastikan customer hanya bisa melihat tiket miliknya sendiri
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $pesanan->load(['paketWisata', 'jadwal', 'jeep', 'supir', 'pembayaran', 'komunitas']);
        return view('frontend.booking.show', compact('pesanan'));
    }

    // 6. Mencetak E-Tiket Customer (Format PDF/Print)
    public function printTicket(Pesanan $pesanan)
    {
        // Pastikan tiket ini benar milik user yang sedang login
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Ini bukan tiket Anda.');
        }

        $pesanan->load(['paketWisata', 'jadwal', 'jeep', 'supir', 'pembayaran', 'komunitas']);
        return view('frontend.booking.print', compact('pesanan'));
    }


}