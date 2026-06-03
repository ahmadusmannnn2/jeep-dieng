<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jeep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komunitas_id')->constrained('komunitas')->cascadeOnDelete();
            $table->string('nama_jeep');
            $table->string('nomor_polisi')->unique();
            $table->integer('kapasitas');
            $table->enum('status', ['Tersedia', 'Disewa', 'Perbaikan'])->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jeep');
    }
};