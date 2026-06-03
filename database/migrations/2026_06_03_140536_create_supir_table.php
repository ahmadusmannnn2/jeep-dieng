<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komunitas_id')->constrained('komunitas')->cascadeOnDelete();
            $table->string('nama_supir');
            $table->string('no_hp')->nullable();
            $table->enum('status', ['Tersedia', 'Sedang Bertugas', 'Tidak Aktif'])->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supir');
    }
};