<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Komunitas;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed 3 user (Admin, Pengelola, Customer) dan 1 Komunitas bawaan.
     */
    public function run(): void
    {
        // Buat 1 Komunitas Dummy agar Pengelola punya Induk
        $komunitas = Komunitas::create([
            'nama_komunitas' => 'Komunitas Jeep Dieng (Pusat)',
            'alamat'         => 'Terminal Sikunir, Dieng',
            'kontak'         => '08222222222',
        ]);

        $password = Hash::make('11111111');

        // 1. Admin Pusat
        User::create([
            'name'         => 'Administrator',
            'email'        => 'admin@gmail.com',
            'password'     => $password,
            'role'         => 'admin',
            'komunitas_id' => null,
            'no_hp'        => '08111111111',
        ]);

        // 2. Pengelola / Ketua Komunitas
        User::create([
            'name'         => 'Ketua Pengelola',
            'email'        => 'pengelola@gmail.com',
            'password'     => $password,
            'role'         => 'pengelola',
            'komunitas_id' => $komunitas->id, // Dihubungkan ke Komunitas di atas
            'no_hp'        => '08222222222',
        ]);

        // 3. Customer
        User::create([
            'name'         => 'Customer Demo',
            'email'        => 'customer@gmail.com',
            'password'     => $password,
            'role'         => 'customer',
            'komunitas_id' => null,
            'no_hp'        => '08333333333',
        ]);
    }
}