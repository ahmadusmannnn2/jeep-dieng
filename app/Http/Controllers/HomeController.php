<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaketWisata;
use App\Models\RuteWisata;
use App\Models\KontenInformasi;

class HomeController extends Controller
{
    // Halaman Beranda (Bisa menampilkan ringkasan atau hero banner saja)
    public function index()
    {
        // Ambil data terbatas untuk ringkasan (etalase) di Home
        $paket = PaketWisata::with('komunitas')->latest()->take(3)->get();
        $rute = RuteWisata::latest()->take(4)->get();
        $promo = KontenInformasi::latest()->take(3)->get();
        
        return view('frontend.home', compact('paket', 'rute', 'promo'));
    }

    // Halaman Khusus Daftar Paket Wisata
    public function paket()
    {
        $paket = PaketWisata::with('komunitas')->latest()->get();
        return view('frontend.paket', compact('paket'));
    }

    // Halaman Khusus Daftar Rute
    public function rute()
    {
        $rute = RuteWisata::latest()->get();
        return view('frontend.rute', compact('rute'));
    }

    // Halaman Khusus Promo & Artikel
    public function promo()
    {
        $promo = KontenInformasi::latest()->get();
        return view('frontend.promo', compact('promo'));
    }

    public function galeri()
    {
        // Data gambar galeri sudah otomatis dibagikan secara global 
        // melalui AppServiceProvider, jadi kita cukup memanggil view-nya saja.
        return view('frontend.galeri');
    }
// Method untuk menampilkan detail paket (Gaya e-commerce)
    public function showPaket(PaketWisata $paketWisata)
    {
        // Muat relasi komunitas agar nama komunitasnya bisa ditampilkan
        $paketWisata->load('komunitas');
        
        return view('frontend.paket.show', compact('paketWisata'));
    }
    
}