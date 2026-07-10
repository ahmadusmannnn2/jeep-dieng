<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE penarikan_saldos MODIFY COLUMN status ENUM('Diajukan', 'Ditransfer', 'Selesai', 'Ditolak') DEFAULT 'Diajukan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE penarikan_saldos MODIFY COLUMN status ENUM('Diajukan', 'Selesai', 'Ditolak') DEFAULT 'Diajukan'");
    }
};
