<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SupirController;
use App\Http\Controllers\Admin\JeepController;
use App\Http\Controllers\Admin\PaketWisataController;
use App\Http\Controllers\Admin\RuteWisataController;
use App\Http\Controllers\Admin\JadwalKeberangkatanController;
use App\Http\Controllers\Admin\KontenInformasiController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\PengaturanController;

// --- RUTE HALAMAN DEPAN (CUSTOMER) ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket-wisata', [HomeController::class, 'paket'])->name('paket');

Route::get('/paket-wisata/{paketWisata}', [HomeController::class, 'showPaket'])->name('paket.show');
Route::get('/rute-trip', [HomeController::class, 'rute'])->name('rute');
Route::get('/info-promo', [HomeController::class, 'promo'])->name('promo');
Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');

// Dashboard Bawaan Breeze (Menampilkan daftar pesanan Customer)
Route::get('/dashboard', function () {
    $pesanan = \App\Models\Pesanan::with(['paketWisata', 'jadwal', 'pembayaran'])
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('dashboard', compact('pesanan'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Profil Bawaan Breeze & Rute Booking Customer
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Booking untuk Customer
    Route::get('/booking/{paketWisata}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{paketWisata}', [BookingController::class, 'store'])->name('booking.store');

    Route::get('/pesanan/{pesanan}/bayar', [BookingController::class, 'payment'])->name('booking.payment');
    Route::post('/pesanan/{pesanan}/bayar', [BookingController::class, 'paymentStore'])->name('booking.payment.store');

    // DETAIL RIWAYAT PESANAN / E-TIKET CUSTOMER
    Route::get('/pesanan-saya/{pesanan}', [BookingController::class, 'show'])->name('booking.show');
    // RUTE CETAK TIKET
    Route::get('/pesanan-saya/{pesanan}/cetak', [BookingController::class, 'printTicket'])->name('booking.print');

});

// RUTE KHUSUS ADMIN
Route::middleware(['auth', 'role:admin,pengelola'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('komunitas', \App\Http\Controllers\Admin\KomunitasController::class)->parameters([
        'komunitas' => 'komunitas'
    ]);
    Route::resource('supir', SupirController::class);
    Route::resource('jeep', JeepController::class);
    Route::resource('paket-wisata', PaketWisataController::class)->parameters([
        'paket-wisata' => 'paketWisata'
    ]);

    Route::resource('rute-wisata', RuteWisataController::class)->parameters([
        'rute-wisata' => 'ruteWisata'
    ]);
    Route::resource('jadwal', JadwalKeberangkatanController::class);
    Route::resource('konten-informasi', KontenInformasiController::class);
    
    Route::resource('testimoni', \App\Http\Controllers\Admin\TestimoniController::class);
    Route::patch('testimoni/{testimoni}/toggle', [\App\Http\Controllers\Admin\TestimoniController::class, 'toggle'])->name('testimoni.toggle');

    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::get('/pesanan/{pesanan}/edit', [PesananController::class, 'edit'])->name('pesanan.edit');
    Route::put('/pesanan/{pesanan}', [PesananController::class, 'update'])->name('pesanan.update');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/komunitas', [LaporanController::class, 'komunitas'])->name('laporan.komunitas');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');


    // PENGATURAN UMUM WEBSITE
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');



});

// --- RUTE MIDTRANS ---
// Webhook notifikasi dari Midtrans server (tanpa auth, diverifikasi via signature)
Route::post('/midtrans/notification', [\App\Http\Controllers\MidtransController::class, 'notification'])->name('midtrans.notification');
// Callback setelah user selesai di Snap
Route::get('/midtrans/finish',   [\App\Http\Controllers\MidtransController::class, 'finish'])->name('midtrans.finish');
Route::get('/midtrans/unfinish', [\App\Http\Controllers\MidtransController::class, 'unfinish'])->name('midtrans.unfinish');
Route::get('/midtrans/error',    [\App\Http\Controllers\MidtransController::class, 'error'])->name('midtrans.error');

require __DIR__ . '/auth.php';