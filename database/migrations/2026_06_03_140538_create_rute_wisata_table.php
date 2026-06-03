<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rute_wisata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komunitas_id')->constrained('komunitas')->cascadeOnDelete();
            $table->string('nama_rute');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rute_wisata');
    }
};