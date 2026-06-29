<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // 1. Tambahkan titik_jemput (karena sebelumnya belum ada di db v4)
            $table->string('titik_jemput')->nullable()->after('tanggal_jadwal');
            
            // 2. Tambahkan tipe_trip dan jumlah_jeep
            $table->enum('tipe_trip', ['Private', 'Group'])->default('Private')->after('titik_jemput');
            $table->integer('jumlah_jeep')->default(1)->after('tipe_trip');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['titik_jemput', 'tipe_trip', 'jumlah_jeep']);
        });
    }
};