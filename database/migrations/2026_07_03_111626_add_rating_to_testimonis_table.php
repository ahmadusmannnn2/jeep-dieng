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
        Schema::table('testimonis', function (Blueprint $table) {
            $table->integer('rating')->default(5)->after('id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('rating');
            $table->foreignId('pesanan_id')->nullable()->constrained('pesanan')->nullOnDelete()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonis', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['pesanan_id']);
            $table->dropColumn(['rating', 'user_id', 'pesanan_id']);
        });
    }
};
