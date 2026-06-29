<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Order ID unik untuk Midtrans (format: BKG-{pesanan_id}-{timestamp})
            $table->string('midtrans_order_id')->nullable()->unique()->after('pesanan_id');
            // Snap token dari Midtrans (kadaluarsa 24 jam)
            $table->string('snap_token', 1000)->nullable()->after('midtrans_order_id');
            // Channel pembayaran yang dipilih user (gopay, bca_va, dll)
            $table->string('payment_channel')->nullable()->after('snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['midtrans_order_id', 'snap_token', 'payment_channel']);
        });
    }
};
