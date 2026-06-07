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
        Schema::table('pesanan', function (Blueprint $table) {
            $table->enum('tipe_pembayaran', ['Lunas', 'DP'])->default('Lunas')->after('total_harga');
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pesanan MODIFY COLUMN status ENUM('Pending', 'Disetujui', 'DP Lunas', 'Lunas', 'Selesai', 'Dibatalkan') DEFAULT 'Pending'");

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->enum('jenis_pembayaran', ['DP', 'Pelunasan', 'Lunas'])->default('Lunas')->after('pesanan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pesanan MODIFY COLUMN status ENUM('Pending', 'Disetujui', 'Lunas', 'Selesai', 'Dibatalkan') DEFAULT 'Pending'");

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('tipe_pembayaran');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('jenis_pembayaran');
        });
    }
};
