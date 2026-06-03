<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin_komunitas', 'customer'])->default('customer')->after('email');
            $table->foreignId('komunitas_id')->nullable()->constrained('komunitas')->cascadeOnDelete()->after('role');
            $table->string('no_hp')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['komunitas_id']);
            $table->dropColumn(['role', 'komunitas_id', 'no_hp']);
        });
    }
};