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
        Schema::table('rute_wisata', function (Blueprint $table) {
            $table->dropForeign(['komunitas_id']);
            $table->dropColumn('komunitas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rute_wisata', function (Blueprint $table) {
            $table->foreignId('komunitas_id')->nullable()->constrained('komunitas')->cascadeOnDelete();
        });
    }
};
