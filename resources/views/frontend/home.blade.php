@extends('frontend.layouts.app')

@section('title', ($pengaturan_website->nama_website ?? 'Jeep Dieng') . ' - Jelajahi Keindahan Alam Dieng')

@section('content')

@php
    // Hero Images Fallback
    $defaultHero = [
        'https://images.unsplash.com/photo-1519985176271-adb1088fa94c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80',
        'https://images.unsplash.com/photo-1533692328991-08159ff19fca?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80'
    ];
    $heroImages = (isset($pengaturan_website) && !empty($pengaturan_website->hero_images)) 
        ? array_map(function($img) { return asset('storage/' . $img); }, $pengaturan_website->hero_images)
        : $defaultHero;
    $heroImagesJson = json_encode($heroImages);

    // Gallery Images Fallback
    $defaultGallery = [
        'https://images.unsplash.com/photo-1533692328991-08159ff19fca?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1542281286-9e0a16bb7366?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1535492984851-bc015f3e2ff5?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=800&q=80',
        'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1517581177682-a085bb7ffb15?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=600&q=80',
        'https://images.unsplash.com/photo-1520645521318-f06a70e20113?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=700&q=80'
    ];
    $galleryImages = (isset($pengaturan_website) && !empty($pengaturan_website->gallery_images)) 
        ? array_map(function($img) { return asset('storage/' . $img); }, $pengaturan_website->gallery_images)
        : $defaultGallery;
@endphp

<section x-data="{ currentSlide: 0, images: {{ $heroImagesJson }} }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % images.length }, 4000)" class="relative w-full h-[85vh] min-h-[600px] flex flex-col items-center justify-center overflow-hidden">
    <template x-for="(img, index) in images" :key="index">
        <img :src="img" alt="Hero Image" class="absolute inset-0 w-full h-full object-cover object-center transition-opacity duration-1000 ease-in-out z-0" :class="currentSlide === index ? 'opacity-100' : 'opacity-0'" />
    </template>
    
    <div class="absolute inset-0 bg-gradient-to-b from-gray-900/90 via-gray-900/80 to-gray-900/90 z-0"></div>
    
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center pb-28 md:pb-40 mt-10">
        <span class="px-4 py-1.5 rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-sm tracking-widest uppercase mb-6 border border-emerald-500/30 backdrop-blur-md">
            {{ $pengaturan_website->hero_badge ?? 'Lebih dari 3 Komunitas Bergabung' }}
        </span>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white tracking-tight mb-6 leading-tight drop-shadow-lg">
            {{ $pengaturan_website->hero_title ?? 'Jelajahi' }} <br class="md:hidden">
            <span class="text-emerald-400">{{ $pengaturan_website->hero_title_highlight ?? 'Keindahan Dieng Tanpa Batas' }}</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-200 max-w-2xl mb-10 leading-relaxed drop-shadow-md">
            {{ $pengaturan_website->hero_subtitle ?? 'Pesan layanan Jeep tangguh untuk menaklukkan medan Dieng. Nikmati Golden Sunrise Sikunir dan Kawah Sikidang dengan aman dan nyaman bersama supir profesional kami.' }}
        </p>
        <div class="flex flex-col sm:flex-row gap-4 relative z-30">
            <a href="{{ route('paket') }}" class="px-8 py-4 bg-emerald-500 text-white font-bold rounded-2xl hover:bg-emerald-400 transition shadow-[0_0_20px_rgba(16,185,129,0.4)] transform hover:-translate-y-1">Lihat Paket Wisata</a>
            <a href="{{ route('promo') }}" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl hover:bg-white/20 transition border border-white/20">Promo Terbaru</a>
        </div>
        <div class="absolute bottom-16 md:bottom-24 flex gap-2">
            <template x-for="(img, index) in images" :key="index">
                <button @click="currentSlide = index" class="w-2 h-2 rounded-full transition-all duration-300" :class="currentSlide === index ? 'bg-emerald-500 w-6' : 'bg-white/50 hover:bg-white'"></button>
            </template>
        </div>
    </div>
    
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-10 translate-y-[1px] pointer-events-none">
        <svg class="relative block w-full h-[60px] md:h-[100px] lg:h-[150px]" fill="#F9FAFB" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V120H0Z"></path></svg>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Paket Populer</h2>
                <p class="mt-4 text-gray-500 max-w-2xl">Pilihan favorit wisatawan untuk menjelajahi pesona Dieng.</p>
            </div>
            <a href="{{ route('paket') }}" class="inline-flex items-center gap-2 text-emerald-600 font-bold hover:text-emerald-700 transition">Lihat Semua Paket <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($paket as $item)
                <div class="bg-white rounded-3xl p-8 border border-gray-100 hover:border-emerald-500/30 hover:shadow-xl transition flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-emerald-100 text-emerald-700">{{ $item->komunitas->nama_komunitas ?? 'Umum' }}</span>
                            <div class="flex items-center gap-1 text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span class="text-sm font-medium">{{ $item->durasi }}</span></div>
                        </div>
                        <h3 class="text-2xl font-extrabold mb-3 text-gray-900">{{ $item->nama_paket }}</h3>
                        <p class="mb-6 text-sm leading-relaxed text-gray-600 line-clamp-2">{{ $item->deskripsi ?? 'Tidak ada deskripsi spesifik.' }}</p>
                    </div>
                    <div class="pt-6 border-t border-gray-100 flex flex-col gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider mb-1 text-gray-500">Mulai</p>
                            <p class="text-xl font-black text-emerald-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                        
                        <div class="flex gap-2 w-full mt-2">
                            <a href="{{ route('paket.show', $item->id) }}" class="flex-1 px-4 py-3 rounded-xl font-bold text-center transition bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-100">
                                Detail
                            </a>
                            <a href="{{ route('booking.create', $item->id) }}" class="flex-1 px-4 py-3 rounded-xl font-bold text-center transition bg-gray-900 text-white hover:bg-emerald-500 shadow-md hover:shadow-emerald-500/30">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-gray-500">Belum ada paket wisata yang tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-20 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16">
        <div>
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900">Rute Destinasi</h2>
                <a href="{{ route('rute') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($rute as $item)
                <div class="p-4 rounded-2xl border border-gray-100 flex items-start gap-4 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg></div>
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $item->nama_rute }}</h4>
                        <p class="text-sm text-gray-500 truncate max-w-xs md:max-w-sm">{{ $item->deskripsi }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Belum ada data rute destinasi.</p>
                @endforelse
            </div>
        </div>

        <div>
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900">Info & Promo</h2>
                <a href="{{ route('promo') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            <div class="space-y-6">
                @forelse($promo as $item)
                <div class="flex gap-4 group cursor-pointer">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden shrink-0 bg-gray-100 relative">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full bg-emerald-100 flex items-center justify-center text-emerald-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg></div>
                        @endif
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="text-xs font-bold text-emerald-500 mb-1">{{ \Carbon\Carbon::parse($item->tanggal_publish)->translatedFormat('d M Y') }}</span>
                        <h4 class="font-bold text-gray-900 group-hover:text-emerald-600 transition line-clamp-2">{{ $item->judul }}</h4>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Belum ada info atau promo terbaru.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-4">Galeri Petualangan</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Potret keseruan wisatawan menaklukkan medan ekstrem dan menikmati golden sunrise Dieng bersama kami.</p>
        </div>

        <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
            @foreach($galleryImages as $index => $img)
                <div class="relative group rounded-2xl overflow-hidden break-inside-avoid shadow-sm hover:shadow-xl transition duration-300">
                    <img src="{{ $img }}" alt="Galeri {{ $index + 1 }}" class="w-full h-auto object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <span class="text-white font-bold tracking-wider">Jeep Adventure</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('galeri') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white text-gray-900 border border-gray-200 font-bold rounded-xl hover:bg-gray-50 transition shadow-sm">
                Lihat Lebih Banyak <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-4">Kata Mereka</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Pengalaman nyata dari wisatawan yang telah mempercayakan perjalanan mereka bersama kami.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-50 p-8 rounded-3xl relative">
                <div class="text-emerald-500 mb-4 flex gap-1">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <p class="text-gray-600 mb-6 italic">"Pelayanan sangat profesional! Driver ramah dan sangat hafal medan. Sunrise di Sikunir adalah pengalaman tak terlupakan. Sangat direkomendasikan!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold">B</div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Budi Santoso</h4>
                        <p class="text-xs text-gray-500">Jakarta, ID</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-8 rounded-3xl relative">
                <div class="text-emerald-500 mb-4 flex gap-1">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <p class="text-gray-600 mb-6 italic">"Pemesanan sangat mudah lewat website. Tinggal pesan, bayar, dan driver sudah standby di homestay. Prosesnya sangat transparan!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold">R</div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Rina Amelia</h4>
                        <p class="text-xs text-gray-500">Surabaya, ID</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-8 rounded-3xl relative">
                <div class="text-emerald-500 mb-4 flex gap-1">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <p class="text-gray-600 mb-6 italic">"Harga bersaing dan pelayanan mantap. Armada Jeep-nya bersih dan terawat dengan baik. Bakal kembali pesen di sini kalau liburan ke Dieng lagi!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">D</div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Deni Setiawan</h4>
                        <p class="text-xs text-gray-500">Semarang, ID</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-gray-50 border-t border-gray-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-4">Pertanyaan Populer (FAQ)</h2>
            <p class="text-gray-500">Jawaban cepat untuk pertanyaan yang sering diajukan calon wisatawan.</p>
        </div>

        <div class="space-y-4" x-data="{ active: null }">
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <button @click="active !== 1 ? active = 1 : active = null" class="w-full px-6 py-4 flex justify-between items-center text-left focus:outline-none">
                    <span class="font-bold text-gray-900">Satu armada Jeep muat berapa orang?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': active === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 1" x-collapse>
                    <div class="px-6 pb-5 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                        Satu armada Jeep memiliki kapasitas maksimal hingga 5-6 orang dewasa (termasuk penumpang di kursi depan). Kami sangat mengutamakan kenyamanan dan keselamatan Anda.
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <button @click="active !== 2 ? active = 2 : active = null" class="w-full px-6 py-4 flex justify-between items-center text-left focus:outline-none">
                    <span class="font-bold text-gray-900">Apakah tiket masuk wisata sudah termasuk dalam harga paket?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': active === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 2" x-collapse>
                    <div class="px-6 pb-5 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                        Secara umum, harga paket yang tertera <strong>hanya biaya sewa Jeep beserta BBM dan jasa driver</strong>. Harga belum termasuk retribusi/tiket masuk ke masing-masing objek wisata (seperti tiket Sikunir atau Kawah Sikidang).
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <button @click="active !== 3 ? active = 3 : active = null" class="w-full px-6 py-4 flex justify-between items-center text-left focus:outline-none">
                    <span class="font-bold text-gray-900">Di mana lokasi titik kumpul (Meeting Point)?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': active === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 3" x-collapse>
                    <div class="px-6 pb-5 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                        Meeting point default berada di area Terminal/Basecamp Dieng atau homestay tempat Anda menginap (di area sekitar Dieng). Anda dapat menuliskan detail penjemputan di kolom catatan saat melakukan booking.
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <button @click="active !== 4 ? active = 4 : active = null" class="w-full px-6 py-4 flex justify-between items-center text-left focus:outline-none">
                    <span class="font-bold text-gray-900">Bagaimana jika cuaca buruk / hujan badai?</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" :class="{ 'rotate-180': active === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 4" x-collapse>
                    <div class="px-6 pb-5 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                        Keselamatan adalah prioritas utama. Jika cuaca dinilai sangat ekstrem dan membahayakan oleh tim pengelola lapangan, perjalanan dapat dijadwal ulang (reschedule) atau dana dapat dikembalikan (refund) sesuai kesepakatan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection