<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class RunMidtransTest extends Seeder
{
    public function run()
    {
        // 1. Simulasikan Auth user
        $user = \App\Models\User::find(3);
        Auth::login($user);

        // 2. Ambil pesanan
        $pesanan = Pesanan::find(1001);
        if (!$pesanan) {
            $this->command->error("Pesanan 1001 tidak ditemukan.");
            return;
        }

        // 3. Panggil method payment di BookingController untuk generate snap token
        $this->command->info("Mencoba membuat Snap Token untuk Pesanan ID 1001...");
        
        $controller = new \App\Http\Controllers\BookingController();
        $response = $controller->payment($pesanan);

        // 4. Ambil Pembayaran terbaru untuk melihat midtrans_order_id & snap_token
        $pembayaran = Pembayaran::where('pesanan_id', 1001)->latest()->first();

        if ($pembayaran) {
            $this->command->info("====================================");
            $this->command->info("Snap Token Berhasil Dibuat!");
            $this->command->info("Midtrans Order ID: " . $pembayaran->midtrans_order_id);
            $this->command->info("Snap Token: " . $pembayaran->snap_token);
            $this->command->info("Status Pembayaran: " . $pembayaran->status);
            $this->command->info("Jenis Pembayaran: " . $pembayaran->jenis_pembayaran);
            $this->command->info("Jumlah Bayar: " . $pembayaran->jumlah_bayar);
            $this->command->info("====================================");
            
            // Simpan ke file cache/tmp untuk digunakan di script notification nanti
            file_put_contents(base_path('midtrans_test_data.json'), json_encode([
                'order_id' => $pembayaran->midtrans_order_id,
                'snap_token' => $pembayaran->snap_token
            ]));
        } else {
            $this->command->error("Gagal membuat Pembayaran / Snap Token. Silakan cek Laravel log.");
        }
    }
}
