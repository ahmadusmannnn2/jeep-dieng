<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->string('metode_pembayaran'); 
            $table->dateTime('tanggal_bayar')->nullable();
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('bukti_bayar')->nullable(); 
            $table->enum('status', ['Menunggu Verifikasi', 'Valid', 'Tidak Valid'])->default('Menunggu Verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};