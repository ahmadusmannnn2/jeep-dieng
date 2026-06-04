<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Pengaturan;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Mengamankan dari error jika tabel belum dimigrasi (misal saat instalasi awal)
        try {
            // Ambil data pengaturan pertama
            $pengaturan = Pengaturan::first();
            // Bagikan variabel ini ke SELURUH file blade (views)
            View::share('pengaturan_website', $pengaturan);
        } catch (\Exception $e) {
            View::share('pengaturan_website', null);
        }
    }
}