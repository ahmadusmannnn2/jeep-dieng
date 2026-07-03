<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE pesanan MODIFY COLUMN status ENUM('Pending', 'Disetujui', 'DP Lunas', 'Selesai Perjalanan', 'Lunas', 'Selesai', 'Dibatalkan') DEFAULT 'Pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE pesanan MODIFY COLUMN status ENUM('Pending', 'Disetujui', 'DP Lunas', 'Lunas', 'Selesai', 'Dibatalkan') DEFAULT 'Pending'");
        }
    }
};
