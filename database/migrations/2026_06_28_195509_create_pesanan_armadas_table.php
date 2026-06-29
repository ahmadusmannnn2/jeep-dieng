<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_armadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            
            // Nullable karena Admin mungkin belum menugaskan armada saat pesanan baru masuk
            $table->foreignId('jeep_id')->nullable()->constrained('jeep')->nullOnDelete();
            $table->foreignId('supir_id')->nullable()->constrained('supir')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_armadas');
    }
};