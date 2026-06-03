<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Komunitas;
use App\Models\Supir;
use App\Models\Jeep;
use App\Models\PaketWisata;
use App\Models\RuteWisata;
use App\Models\JadwalKeberangkatan;
use App\Models\KontenInformasi;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Komunitas
        $komunitas1 = Komunitas::create([
            'nama_komunitas' => 'Komunitas Jeep Sikunir',
            'alamat' => 'Desa Sembungan, Kejajar, Wonosobo',
            'kontak' => '081234567890'
        ]);

        $komunitas2 = Komunitas::create([
            'nama_komunitas' => 'Komunitas Jeep Kawah Sikidang',
            'alamat' => 'Dieng Kulon, Batur, Banjarnegara',
            'kontak' => '081987654321'
        ]);

        // 2. Buat Akun Pengguna (Password: 11111111)
        $password = Hash::make('11111111');

        // Super Admin (Tidak terikat komunitas tertentu)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => $password,
            'role' => 'super_admin',
            'komunitas_id' => null,
            'no_hp' => '080000000000',
        ]);

        // Admin Komunitas 1
        User::create([
            'name' => 'Admin Sikunir',
            'email' => 'admin@gmail.com',
            'password' => $password,
            'role' => 'admin_komunitas',
            'komunitas_id' => $komunitas1->id,
            'no_hp' => '081111111111',
        ]);

        // Admin Komunitas 2
        User::create([
            'name' => 'Admin Sikidang',
            'email' => 'admin2@gmail.com',
            'password' => $password,
            'role' => 'admin_komunitas',
            'komunitas_id' => $komunitas2->id,
            'no_hp' => '082222222222',
        ]);

        // Customer 1
        User::create([
            'name' => 'Customer Satu',
            'email' => '1@gmail.com',
            'password' => $password,
            'role' => 'customer',
            'komunitas_id' => null,
            'no_hp' => '083333333333',
        ]);

        // Customer 2
        User::create([
            'name' => 'Customer Dua',
            'email' => '2@gmail.com',
            'password' => $password,
            'role' => 'customer',
            'komunitas_id' => null,
            'no_hp' => '084444444444',
        ]);

        // 3. Buat Data Master untuk Komunitas 1 (Sikunir)
        Supir::create(['komunitas_id' => $komunitas1->id, 'nama_supir' => 'Budi Santoso', 'no_hp' => '085111111', 'status' => 'Tersedia']);
        Supir::create(['komunitas_id' => $komunitas1->id, 'nama_supir' => 'Agus Hariyanto', 'no_hp' => '085222222', 'status' => 'Tersedia']);

        Jeep::create(['komunitas_id' => $komunitas1->id, 'nama_jeep' => 'Hardtop Biru', 'nomor_polisi' => 'AA 1234 BB', 'kapasitas' => 4, 'status' => 'Tersedia']);
        Jeep::create(['komunitas_id' => $komunitas1->id, 'nama_jeep' => 'Land Rover Hijau', 'nomor_polisi' => 'AA 5678 CC', 'kapasitas' => 4, 'status' => 'Tersedia']);

        PaketWisata::create([
            'komunitas_id' => $komunitas1->id,
            'nama_paket' => 'Paket Sunrise Sikunir',
            'harga' => 500000,
            'durasi' => '4 Jam',
            'deskripsi' => 'Menikmati golden sunrise dari Bukit Sikunir dengan layanan eksklusif.'
        ]);

        RuteWisata::create(['komunitas_id' => $komunitas1->id, 'nama_rute' => 'Basecamp - Bukit Sikunir - Telaga Cebong', 'deskripsi' => 'Rute favorit untuk melihat sunrise di pagi hari.']);

        JadwalKeberangkatan::create(['komunitas_id' => $komunitas1->id, 'tanggal' => date('Y-m-d', strtotime('+1 day')), 'jam' => '03:00:00']);
        JadwalKeberangkatan::create(['komunitas_id' => $komunitas1->id, 'tanggal' => date('Y-m-d', strtotime('+2 days')), 'jam' => '03:00:00']);

        // 4. Buat Konten Informasi untuk Landing Page
        KontenInformasi::create([
            'judul' => 'Promo Wisata Jeep Dieng Bulan Ini!',
            'isi' => 'Dapatkan pengalaman luar biasa berkeliling Dieng menggunakan Jeep tangguh. Segera amankan jadwal Anda sebelum kehabisan.',
            'tanggal_publish' => date('Y-m-d')
        ]);
    }
}