<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Tambah kolom tanggal_jadwal yang dipakai di BookingController
            $table->date('tanggal_jadwal')->nullable()->after('jadwal_id');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('tanggal_jadwal');
        });
    }
};
