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
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_keberangkatan,id',
            'jumlah_pengunjung' => 'required|integer|min:1|max:6', // Maksimal 6 orang per Jeep
            'catatan' => 'nullable|string'
        ]);

        // Harga tetap per paket (per Jeep), jumlah pengunjung hanya untuk informasi
        $totalHarga = $paketWisata->harga;

        Pesanan::create([
            'user_id' => Auth::id(),
            'komunitas_id' => $paketWisata->komunitas_id,
            'paket_wisata_id' => $paketWisata->id,
            'jadwal_id' => $request->jadwal_id,
            'jumlah_pengunjung' => $request->jumlah_pengunjung,
            'total_harga' => $totalHarga,
            'status' => 'Pending',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('dashboard')->with('success', 'Booking berhasil dibuat! Menunggu persetujuan admin sebelum Anda melakukan pembayaran.');
    }

    // 3. Menampilkan Form Upload Pembayaran
    public function payment(Pesanan $pesanan)
    {
        // Pastikan pesanan ini milik user yang login dan statusnya disetujui
        if ($pesanan->user_id !== Auth::id() || $pesanan->status !== 'Disetujui') {
            abort(403);
        }

        return view('frontend.booking.payment', compact('pesanan'));
    }

    // 4. Memproses Upload Bukti Bayar
    public function paymentStore(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->user_id !== Auth::id() || $pesanan->status !== 'Disetujui') {
            abort(403);
        }

        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = $request->file('bukti_bayar')->store('pembayaran', 'public');

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_bayar' => now(),
            'jumlah_bayar' => $pesanan->total_harga,
            'bukti_bayar' => $path,
            'status' => 'Menunggu Verifikasi'
        ]);

        return redirect()->route('dashboard')->with('success', 'Bukti pembayaran berhasil diunggah! Admin akan segera memverifikasinya.');
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