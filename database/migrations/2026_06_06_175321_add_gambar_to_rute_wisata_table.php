<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration (Menambah kolom gambar)
     */
    public function up(): void
    {
        Schema::table('rute_wisata', function (Blueprint $table) {
            // Menambahkan kolom gambar setelah kolom deskripsi
            $table->string('gambar')->nullable()->after('deskripsi');
        });
    }

    /**
     * Batalkan migration (Menghapus kolom gambar)
     */
    public function down(): void
    {
        Schema::table('rute_wisata', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
};