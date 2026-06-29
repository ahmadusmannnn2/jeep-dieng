<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed hanya 3 user untuk keperluan uji coba.
     * Data lain (komunitas, paket, jeep, dll) diisi oleh admin melalui panel.
     *
     * Semua password: password123
     */
    public function run(): void
    {
        // 1. Admin Pusat
        User::create([
            'name'         => 'Administrator',
            'email'        => 'admin@jeepdieng.com',
            'password'     => Hash::make('password123'),
            'role'         => 'admin',
            'komunitas_id' => null,
            'no_hp'        => null,
        ]);


        // 3. Customer
        User::create([
            'name'         => 'Customer Demo',
            'email'        => 'customer@jeepdieng.com',
            'password'     => Hash::make('password123'),
            'role'         => 'customer',
            'komunitas_id' => null,
            'no_hp'        => null,
        ]);
    }
}