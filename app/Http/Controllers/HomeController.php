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
        $paket = PaketWisata::with('komunitas')->latest()->take(3)->get(); // Tampilkan 3 saja di home
        return view('frontend.home', compact('paket'));
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
}