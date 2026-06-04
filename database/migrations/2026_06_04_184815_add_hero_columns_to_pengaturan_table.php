<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_title_highlight')->nullable(); // Bagian teks yang berwarna hijau
            $table->text('hero_subtitle')->nullable();
            $table->json('hero_images')->nullable(); // Untuk menyimpan banyak gambar sekaligus
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->dropColumn(['hero_badge', 'hero_title', 'hero_title_highlight', 'hero_subtitle', 'hero_images']);
        });
    }
};