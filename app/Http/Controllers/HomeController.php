<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaketWisata;
use App\Models\RuteWisata;
use App\Models\KontenInformasi;

use App\Models\Testimoni;

class HomeController extends Controller
{
    // Halaman Beranda (Bisa menampilkan ringkasan atau hero banner saja)
    public function index()
    {
        // Ambil data terbatas untuk ringkasan (etalase) di Home
        $paket = PaketWisata::with(['komunitas', 'rutes'])->latest()->take(3)->get();
        // Ubah nama variabel menjadi ruteWisata agar seragam
        $ruteWisata = RuteWisata::latest()->take(4)->get(); 
        $promo = KontenInformasi::latest()->take(3)->get();
        $testimonis = Testimoni::where('is_tampil', true)->latest()->take(6)->get();
        
        return view('frontend.home', compact('paket', 'ruteWisata', 'promo', 'testimonis'));
    }

    // Halaman Khusus Daftar Paket Wisata
    public function paket(\Illuminate\Http\Request $request)
    {
        $query = PaketWisata::with(['komunitas', 'rutes'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_paket', 'like', "%{$search}%")
                  ->orWhereHas('rutes', function($qRute) use ($search) {
                      $qRute->where('nama_rute', 'like', "%{$search}%");
                  });
            });
        }

        $paket = $query->get();
        $searchTerm = $request->search;
        
        return view('frontend.paket', compact('paket', 'searchTerm'));
    }

    // Halaman Khusus Daftar Rute
    public function rute()
    {
        // PERBAIKAN: Ubah nama variabel menjadi $ruteWisata dan tambahkan with('komunitas')
        $ruteWisata = RuteWisata::latest()->get();
        
        return view('frontend.rute', compact('ruteWisata'));
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
        $paketWisata->load(['komunitas', 'rutes']);
        
        return view('frontend.paket.show', compact('paketWisata'));
    }
}