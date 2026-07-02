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
        // Paksa skema URL ke HTTPS jika diakses melalui proxy aman ngrok/domain HTTPS
        if (str_starts_with(config('app.url'), 'https://') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

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