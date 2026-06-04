<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_website')->default('JEEP DIENG');
            $table->string('logo')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('alamat')->nullable();
            $table->text('deskripsi_footer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
