@extends('frontend.layouts.app')

@section('title', $paketWisata->nama_paket . ' - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))

@section('content')

@php
    // Menggunakan gambar paket jika ada, jika tidak gunakan placeholder
    $mainImage = $paketWisata->gambar ? asset('storage/' . $paketWisata->gambar) : asset('images/placeholder-landscape.svg');
    
    // Mengambil gambar galeri website sebagai thumbnail "suasana"
    $thumbnails = (isset($pengaturan_website) && !empty($pengaturan_website->gallery_images)) 
        ? array_slice(array_map(function($img) { return asset('storage/' . $img); }, $pengaturan_website->gallery_images), 0, 4)
        : [
            asset('images/placeholder-square.svg'),
            asset('images/placeholder-square.svg'),
            asset('images/placeholder-square.svg'),
            asset('images/placeholder-square.svg')
        ];
@endphp

<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <nav class="flex text-sm text-gray-500 mb-8 font-medium">
            <a href="{{ route('home') }}" class="hover:text-emerald-600 transition">Beranda</a>
            <span class="mx-2">/</span>
            <a href="{{ route('paket') }}" class="hover:text-emerald-600 transition">Paket Wisata</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $paketWisata->nama_paket }}</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <div class="lg:col-span-5" x-data="{ mainPhoto: '{{ $mainImage }}' }">
                    <div class="aspect-[4/3] rounded-2xl overflow-hidden mb-4 border border-gray-100 bg-gray-50 group cursor-zoom-in">
                        <img :src="mainPhoto" alt="{{ $paketWisata->nama_paket }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    
                    <div class="grid grid-cols-5 gap-2 md:gap-4">
                        <button @click="mainPhoto = '{{ $mainImage }}'" class="aspect-square rounded-xl overflow-hidden border-2 focus:outline-none transition" :class="mainPhoto === '{{ $mainImage }}' ? 'border-emerald-500 opacity-100' : 'border-transparent opacity-60 hover:opacity-100'">
                            <img src="{{ $mainImage }}" class="w-full h-full object-cover">
                        </button>
                        @foreach($thumbnails as $thumb)
                            <button @click="mainPhoto = '{{ $thumb }}'" class="aspect-square rounded-xl overflow-hidden border-2 focus:outline-none transition" :class="mainPhoto === '{{ $thumb }}' ? 'border-emerald-500 opacity-100' : 'border-transparent opacity-60 hover:opacity-100'">
                                <img src="{{ $thumb }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-7 flex flex-col h-full">
                    
                    <div class="border-b border-gray-100 pb-6 mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-emerald-100 text-emerald-700">
                                {{ $paketWisata->komunitas->nama_komunitas ?? 'Umum' }}
                            </span>
                            <div class="flex items-center gap-1 text-gray-500 bg-gray-100 px-3 py-1 rounded-full text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $paketWisata->durasi ?? 'Estimasi 4-6 Jam' }}
                            </div>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">{{ $paketWisata->nama_paket }}</h1>
                        
                        <div class="flex items-baseline gap-2">
                            <span class="text-gray-500 font-medium">Mulai dari</span>
                            <span class="text-4xl md:text-5xl font-black text-emerald-500">Rp {{ number_format($paketWisata->harga, 0, ',', '.') }}</span>
                            <span class="text-gray-500 font-medium text-sm">/ Armada</span>
                        </div>
                    </div>

                    <div class="flex-grow prose prose-emerald max-w-none text-gray-600 mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Detail Perjalanan</h3>
                        <p class="leading-relaxed whitespace-pre-line">{{ $paketWisata->deskripsi ?? 'Nikmati pengalaman tak terlupakan menjelajahi alam Dieng dengan Jeep tangguh kami.' }}</p>
                        
                        @if($paketWisata->rutes && $paketWisata->rutes->count() > 0)
                        <h3 class="text-xl font-extrabold text-gray-900 mb-6 mt-10 border-b pb-3 border-gray-100">Rute Perjalanan (Itinerary)</h3>
                        <div class="relative pl-6 border-l-[3px] border-emerald-100 space-y-6 mb-10 ml-2">
                            @foreach($paketWisata->rutes as $index => $rute)
                            <div class="relative group">
                                <div class="absolute -left-[35px] bg-emerald-500 w-6 h-6 rounded-full border-4 border-white shadow-sm group-hover:scale-125 group-hover:bg-emerald-400 transition-all duration-300 flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                </div>
                                <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-5 hover:bg-emerald-50 hover:border-emerald-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                    <div class="flex justify-between items-start mb-2 gap-4">
                                        <h4 class="font-black text-gray-900 text-lg group-hover:text-emerald-700 transition-colors">{{ $rute->nama_rute }}</h4>
                                        <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest shrink-0">Titik {{ $index + 1 }}</span>
                                    </div>
                                    @if($rute->deskripsi)
                                        <p class="text-sm text-gray-500 leading-relaxed">{{ $rute->deskripsi }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            
                            <div class="relative mt-8">
                                <div class="absolute -left-[35px] bg-gray-300 w-6 h-6 rounded-full border-4 border-white shadow-sm flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                </div>
                                <h4 class="font-bold text-gray-400 text-sm ml-2 mt-0.5">Perjalanan Selesai</h4>
                            </div>
                        </div>
                        @endif
                        
                        <h3 class="text-lg font-bold text-gray-900 mb-2 mt-8">Fasilitas Termasuk</h3>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 1 Unit Jeep 4x4 Tangguh</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Driver Profesional & Berpengalaman</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Bahan Bakar Minyak (BBM)</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Kapasitas Maks. 5-6 Penumpang</li>
                        </ul>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-6 border-t border-gray-100">
                        
                        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('paket') }}" class="px-6 py-4 text-gray-500 font-bold rounded-2xl hover:bg-gray-100 hover:text-gray-900 transition flex items-center justify-center gap-2 shrink-0 border border-transparent">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </a>

                        <a href="{{ route('booking.create', $paketWisata->id) }}" class="flex-1 px-8 py-4 bg-emerald-500 text-white text-lg font-extrabold rounded-2xl hover:bg-emerald-600 transition shadow-[0_8px_20px_rgba(16,185,129,0.3)] text-center flex items-center justify-center gap-2 transform hover:-translate-y-1">
                            Pesan Sekarang
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        
                    </div>

                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
