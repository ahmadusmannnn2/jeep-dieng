<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); 
            $table->foreignId('komunitas_id')->constrained('komunitas')->cascadeOnDelete();
            $table->foreignId('paket_wisata_id')->constrained('paket_wisata')->cascadeOnDelete();
            $table->foreignId('jadwal_id')->constrained('jadwal_keberangkatan')->cascadeOnDelete();
            $table->foreignId('jeep_id')->nullable()->constrained('jeep')->nullOnDelete();
            $table->foreignId('supir_id')->nullable()->constrained('supir')->nullOnDelete();
            $table->integer('jumlah_pengunjung');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['Pending', 'Disetujui', 'Lunas', 'Selesai', 'Dibatalkan'])->default('Pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};