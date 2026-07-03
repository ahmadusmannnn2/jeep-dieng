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
        Schema::create('paket_rute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_wisata_id')->constrained('paket_wisata')->cascadeOnDelete();
            $table->foreignId('rute_wisata_id')->constrained('rute_wisata')->cascadeOnDelete();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_rute');
    }
};
