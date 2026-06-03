<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_keberangkatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komunitas_id')->constrained('komunitas')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_keberangkatan');
    }
};