@extends('frontend.layouts.app')

@section('content')
<section class="relative bg-gray-900 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 to-emerald-900 opacity-90 z-0"></div>
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 z-0"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-40 flex flex-col items-center text-center">
        <span class="px-4 py-1.5 rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-sm tracking-wide mb-6 border border-emerald-500/30">Lebih dari 3 Komunitas Bergabung</span>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6 leading-tight">
            Jelajahi <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Keindahan Dieng</span><br>Tanpa Batas.
        </h1>
        <p class="text-lg md:text-xl text-gray-300 max-w-2xl mb-10">Pesan layanan Jeep tangguh untuk menaklukkan medan Dieng. Nikmati Golden Sunrise Sikunir dan Kawah Sikidang dengan aman dan nyaman bersama supir profesional kami.</p>
        
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('paket') }}" class="px-8 py-4 bg-emerald-500 text-white font-bold rounded-2xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/40 transform hover:-translate-y-1">Lihat Paket Wisata</a>
            <a href="{{ route('promo') }}" class="px-8 py-4 bg-gray-800 text-white font-bold rounded-2xl hover:bg-gray-700 transition border border-gray-700">Promo Terbaru</a>
        </div>
    </div>
    
    <div class="absolute bottom-0 w-full overflow-hidden leading-none z-10">
        <svg class="relative block w-full h-12 md:h-20" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118,130.83,121.22,201.2,112.5,242.4,107.41,281.71,92.17,321.39,56.44Z" fill="#F9FAFB"></path>
        </svg>
    </div>
</section>
@endsection