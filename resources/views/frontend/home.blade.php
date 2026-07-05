@extends('frontend.layouts.app')

@section('title', ($pengaturan_website->nama_website ?? 'Jeep Dieng') . ' - Jelajahi Keindahan Alam Dieng')

@section('content')

<!-- Load CSS AOS untuk Animasi -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

@php
    // Hero Images Fallback
    $defaultHero = [
        asset('images/placeholder-landscape.svg'),
        asset('images/placeholder-landscape.svg'),
    ];
    $heroImages = (isset($pengaturan_website) && !empty($pengaturan_website->hero_images)) 
        ? array_map(function($img) { return asset('storage/' . $img); }, $pengaturan_website->hero_images)
        : $defaultHero;
    $heroImagesJson = json_encode($heroImages);

    // Gallery Media (Foto + Video) Fallback
    $defaultGallery = [
        ['type' => 'image', 'src' => asset('images/placeholder-landscape.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-square.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-promo.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-landscape.svg')],
    ];
    $galleryMedia = [];
    if (isset($pengaturan_website) && (!empty($pengaturan_website->gallery_images) || !empty($pengaturan_website->gallery_videos))) {
        if (!empty($pengaturan_website->gallery_images)) {
            foreach ($pengaturan_website->gallery_images as $img) {
                $galleryMedia[] = ['type' => 'image', 'src' => asset('storage/' . $img)];
            }
        }
        if (!empty($pengaturan_website->gallery_videos)) {
            foreach ($pengaturan_website->gallery_videos as $vid) {
                $galleryMedia[] = ['type' => 'video', 'src' => asset('storage/' . $vid)];
            }
        }
    } else {
        $galleryMedia = $defaultGallery;
    }
    // Hanya tampilkan 4 item di beranda
    $galleryMedia = array_slice($galleryMedia, 0, 4);
@endphp

<!-- HERO SECTION -->
<section x-data="{ currentSlide: 0, images: {{ $heroImagesJson }} }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % images.length }, 4000)" class="relative w-full h-[85vh] min-h-[600px] flex flex-col items-center justify-center overflow-hidden">
    <template x-for="(img, index) in images" :key="index">
        <img :src="img" alt="Hero Image" class="absolute inset-0 w-full h-full object-cover object-center transition-opacity duration-1000 ease-in-out z-0" :class="currentSlide === index ? 'opacity-100' : 'opacity-0'" />
    </template>
    
    <div class="absolute inset-0 bg-gradient-to-b from-gray-900/90 via-gray-900/80 to-gray-900/90 z-0"></div>
    
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center pb-28 md:pb-40 mt-10">
        <span data-aos="fade-down" data-aos-duration="1000" class="px-4 py-1.5 rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-sm tracking-widest uppercase mb-6 border border-emerald-500/30 backdrop-blur-md">
            {{ $pengaturan_website->hero_badge ?? 'Lebih dari 3 Komunitas Bergabung' }}
        </span>
        <h1 data-aos="zoom-in" data-aos-duration="1200" class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white tracking-tight mb-6 leading-tight drop-shadow-lg">
            {{ $pengaturan_website->hero_title ?? 'Jelajahi' }} <br class="md:hidden">
            <span class="text-emerald-400">{{ $pengaturan_website->hero_title_highlight ?? 'Keindahan Dieng Tanpa Batas' }}</span>
        </h1>
        <p data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200" class="text-lg md:text-xl text-gray-200 max-w-2xl mb-10 leading-relaxed drop-shadow-md">
            {{ $pengaturan_website->hero_subtitle ?? 'Pesan layanan Jeep tangguh untuk menaklukkan medan Dieng. Nikmati Golden Sunrise Sikunir dan Kawah Sikidang dengan aman dan nyaman bersama supir profesional kami.' }}
        </p>
        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400" class="flex flex-col sm:flex-row gap-4 relative z-30">
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

<!-- SECTION PAKET POPULER -->
<section class="py-24 bg-gray-50 relative overflow-hidden">
    <!-- WATERMARK JEEP KIRI ATAS -->
    <img src="{{ asset('assets/img/depan.png') }}" class="absolute -top-10 -left-20 w-96 opacity-[0.03] grayscale blur-[2px] -rotate-12 pointer-events-none select-none z-0" alt="Jeep Watermark">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6" data-aos="fade-right">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">Paket Populer</h2>
                <p class="mt-4 text-gray-500 max-w-2xl text-lg">Pilihan favorit wisatawan untuk menjelajahi pesona Dieng.</p>
            </div>
            <a href="{{ route('paket') }}" class="inline-flex items-center gap-2 text-emerald-600 font-bold hover:text-emerald-700 transition">Lihat Semua Paket <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($paket as $index => $item)
                @php $coverImg = $item->gambar ? asset('storage/' . $item->gambar) : asset('images/placeholder-landscape.svg'); @endphp
                <div data-aos="fade-up" data-aos-delay="{{ $index * 150 }}" class="bg-white rounded-[2rem] overflow-hidden border border-gray-100 hover:border-emerald-500/30 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col group">
                    {{-- GAMBAR COVER --}}
                    <a href="{{ route('paket.show', $item->id) }}" class="block relative overflow-hidden" style="aspect-ratio:16/9;">
                        <img src="{{ $coverImg }}" alt="{{ $item->nama_paket }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/50 via-transparent to-transparent"></div>
                        <span class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-sm text-emerald-700 text-xs font-black uppercase rounded-full tracking-wider">{{ $item->komunitas->nama_komunitas ?? 'Umum' }}</span>
                        <span class="absolute top-3 right-3 flex items-center gap-1 px-2.5 py-1 bg-gray-900/70 text-white text-xs font-bold rounded-full backdrop-blur-sm"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $item->durasi }}</span>
                    </a>
                    {{-- KONTEN --}}
                    <div class="p-7 flex flex-col flex-grow">
                        <h3 class="text-xl font-extrabold mb-2 text-gray-900 group-hover:text-emerald-600 transition leading-tight">{{ $item->nama_paket }}</h3>
                        <p class="mb-3 text-sm leading-relaxed text-gray-500 line-clamp-2">{{ $item->deskripsi ?? 'Nikmati petualangan seru di alam Dieng.' }}</p>
                        
                        {{-- DAFTAR RUTE (ITINERARY) MINI --}}
                        @if($item->rutes && $item->rutes->count() > 0)
                        <div class="mb-5 flex-grow">
                            <h4 class="text-xs font-bold text-gray-900 mb-2 uppercase tracking-wider">Rute Perjalanan:</h4>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($item->rutes as $idx => $rute)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded border border-emerald-100">
                                        {{ $idx + 1 }}. {{ $rute->nama_rute }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="mb-5 flex-grow"></div>
                        @endif

                        <div class="pt-5 border-t border-gray-100 flex flex-col gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider mb-1 text-gray-400 font-bold">Mulai Dari</p>
                                <p class="text-2xl font-black text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex gap-3 w-full">
                                <a href="{{ route('paket.show', $item->id) }}" class="flex-1 px-4 py-3.5 rounded-xl font-bold text-center transition bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-100">Detail</a>
                                <a href="{{ route('booking.create', $item->id) }}" class="flex-1 px-4 py-3.5 rounded-xl font-bold text-center transition bg-gray-900 text-white hover:bg-emerald-500 shadow-lg hover:shadow-emerald-500/30 transform hover:-translate-y-1">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-gray-500">Belum ada paket wisata yang tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- SEKSI INTERAKTIF JEEP BERJALAN (SCROLL ANIMATION) -->
<!-- Menggunakan Alpine.js untuk melacak posisi scroll layar -->
<section x-data="{ scroll: 0, sectionTop: 0, vh: 0, vw: 0, sh: 0 }" 
         x-init="sectionTop = $el.offsetTop; vh = window.innerHeight; vw = window.innerWidth; sh = $el.offsetHeight" 
         @scroll.window="scroll = window.scrollY; sectionTop = $el.offsetTop" 
         @resize.window="vh = window.innerHeight; vw = window.innerWidth; sh = $el.offsetHeight"
         class="relative h-[400px] md:h-[500px] bg-gradient-to-br from-emerald-900 via-gray-900 to-gray-900 overflow-hidden border-y-8 border-emerald-500 flex items-center justify-center">
    
    <!-- Latar Belakang Tulisan Transparan -->
    <h2 class="absolute z-0 text-7xl md:text-[150px] font-black text-white/5 uppercase tracking-tighter whitespace-nowrap select-none">
        JEEP DIENG ADVENTURE
    </h2>

    <!-- Teks Utama -->
    <div class="relative z-20 text-center px-4 -mt-20" data-aos="zoom-in" data-aos-duration="1000">
        <span class="text-emerald-400 font-black tracking-widest uppercase text-sm md:text-base drop-shadow-md">Sensasi Tanpa Batas</span>
        <h3 class="text-4xl md:text-6xl font-extrabold text-white mt-2 drop-shadow-lg italic">TAKLUKKAN MEDANNYA</h3>
    </div>

    <!-- JEEP BERJALAN (TRANSLATE X BERDASARKAN SCROLL) -->
    <!-- Progress = (scroll - sectionTop + vh) / (vh + sh). Transform dari -600px hingga full width + 200px -->
    <img src="{{ asset('assets/img/samping.png') }}" 
         alt="Jeep Adventure" 
         class="absolute bottom-8 md:bottom-10 z-30 h-28 md:h-48 w-auto drop-shadow-[0_25px_25px_rgba(0,0,0,0.9)] transition-transform duration-75 ease-out"
         :style="`left: 0; transform: translateX(${ ((scroll - sectionTop + vh) / (vh + sh || 1)) * (vw + 800) - 600 }px)`">

    <!-- JALANAN (ROAD) -->
    <div class="absolute bottom-0 left-0 w-full h-10 md:h-14 bg-gray-800 border-t-[3px] border-dashed border-yellow-500/50 z-20"></div>
    <div class="absolute bottom-0 left-0 w-full h-5 md:h-7 bg-gray-900 z-20"></div>
</section>

<!-- SECTION RUTE & PROMO -->
<section class="py-24 bg-white relative overflow-hidden">
    <!-- WATERMARK COMPASS / JEEP KANAN BAWAH -->
    <img src="{{ asset('assets/img/depan.png') }}" class="absolute bottom-10 -right-20 w-80 opacity-[0.03] grayscale blur-[2px] rotate-45 pointer-events-none select-none z-0" alt="Jeep Watermark">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 relative z-10">
        
        <!-- KOLOM RUTE -->
        <div data-aos="fade-right">
            <div class="flex justify-between items-end mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Rute Destinasi</h2>
                <a href="{{ route('rute') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($ruteWisata as $item)
                <div class="p-5 rounded-2xl border border-gray-100 flex items-center gap-5 hover:border-emerald-200 hover:shadow-lg transition group cursor-pointer bg-white">
                    <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center text-emerald-500 shrink-0 overflow-hidden relative">
                        @if($item->gambar)
                            <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/placeholder-landscape.svg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg group-hover:text-emerald-600 transition">{{ $item->nama_rute }}</h4>
                        <p class="text-sm text-gray-500 truncate max-w-xs md:max-w-sm mt-1">{{ $item->deskripsi }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Belum ada data rute destinasi.</p>
                @endforelse
            </div>
        </div>

        <!-- KOLOM PROMO -->
        <div data-aos="fade-left">
            <div class="flex justify-between items-end mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Info & Promo</h2>
                <a href="{{ route('promo') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            <div class="space-y-8">
                @forelse($promo as $item)
                <div class="flex gap-5 group cursor-pointer bg-white">
                    <div class="w-28 h-28 rounded-2xl overflow-hidden shrink-0 bg-gray-100 relative shadow-sm">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full bg-emerald-50 flex items-center justify-center text-emerald-400"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg></div>
                        @endif
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="text-xs font-black tracking-widest text-emerald-500 mb-2 uppercase">{{ \Carbon\Carbon::parse($item->tanggal_publish)->translatedFormat('d M Y') }}</span>
                        <h4 class="font-extrabold text-xl text-gray-900 group-hover:text-emerald-600 transition line-clamp-2 leading-tight">{{ $item->judul }}</h4>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Belum ada info atau promo terbaru.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- GALERI -->
<section class="py-24 bg-gray-50 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Galeri Petualangan</h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">Potret keseruan wisatawan menaklukkan medan ekstrem dan menikmati golden sunrise Dieng bersama kami.</p>
        </div>

        <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6 space-y-6">
            @foreach($galleryMedia as $index => $media)
                @if($media['type'] === 'image')
                    <div data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}" class="relative group rounded-3xl overflow-hidden break-inside-avoid shadow-sm hover:shadow-2xl transition duration-500">
                        <img src="{{ $media['src'] }}" alt="Galeri {{ $index + 1 }}" class="w-full h-auto object-cover transform group-hover:scale-110 transition duration-700" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-500 flex items-end p-6">
                            <span class="text-white font-black tracking-widest uppercase text-sm transform translate-y-4 group-hover:translate-y-0 transition duration-500">Jeep Adventure</span>
                        </div>
                    </div>
                @else
                    <div data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}" class="relative group rounded-3xl overflow-hidden break-inside-avoid shadow-sm hover:shadow-2xl transition duration-500">
                        <video src="{{ $media['src'] }}" class="w-full h-auto object-cover" muted preload="metadata"></video>
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/60 transition duration-300 flex items-center justify-center">
                            <div class="w-14 h-14 bg-white/20 border-2 border-white/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white group-hover:scale-110 transition">
                                <svg class="w-7 h-7 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                        <span class="absolute top-3 right-3 bg-purple-600/90 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">VIDEO</span>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-16 text-center" data-aos="fade-up">
            <a href="{{ route('galeri') }}" class="inline-flex items-center justify-center gap-3 px-10 py-4 bg-white text-gray-900 border-2 border-gray-100 font-black text-lg rounded-2xl hover:border-emerald-500 hover:text-emerald-600 transition shadow-sm hover:shadow-xl transform hover:-translate-y-1">
                Lihat Lebih Banyak <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</section>

<!-- TESTIMONI -->
<section class="py-24 bg-white relative overflow-hidden">
    <!-- WATERMARK TENGAH -->
    <img src="{{ asset('assets/img/depan.png') }}" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] opacity-[0.02] grayscale blur-[3px] pointer-events-none select-none z-0">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-20" data-aos="fade-down">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Kata Mereka</h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">Pengalaman nyata dari wisatawan yang telah mempercayakan perjalanan mereka bersama kami.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $testiStyles = [
                    ['bg' => 'bg-white', 'text' => 'text-gray-700', 'name' => 'text-gray-900', 'star' => 'text-emerald-500', 'avatar' => 'from-emerald-400 to-emerald-600'],
                    ['bg' => 'bg-gray-900', 'text' => 'text-gray-300', 'name' => 'text-white', 'star' => 'text-yellow-400', 'avatar' => 'from-yellow-400 to-orange-500', 'extra' => 'transform md:-translate-y-4 shadow-2xl', 'border' => 'border-gray-700'],
                    ['bg' => 'bg-white', 'text' => 'text-gray-700', 'name' => 'text-gray-900', 'star' => 'text-emerald-500', 'avatar' => 'from-blue-400 to-blue-600'],
                ];
            @endphp
            
            @forelse($testimonis as $index => $testi)
                @php $style = $testiStyles[$index % 3]; @endphp
                <div data-aos="fade-up" data-aos-delay="{{ $index * 150 }}" class="{{ $style['bg'] }} p-10 rounded-[2rem] {{ $style['bg'] == 'bg-white' ? 'border border-gray-100 shadow-lg' : '' }} {{ $style['extra'] ?? '' }} hover:shadow-2xl transition duration-300 relative group">
                    <div class="{{ $style['star'] }} mb-6 flex gap-1 transform group-hover:scale-110 transition origin-left">
                        @for($i = 0; $i < ($testi->rating ?? 5); $i++)
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                        @for($i = ($testi->rating ?? 5); $i < 5; $i++)
                            <svg class="w-6 h-6 fill-current text-gray-300 opacity-50" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                    </div>
                    <p class="{{ $style['text'] }} mb-8 italic text-lg font-medium leading-relaxed">"{{ $testi->pesan }}"</p>
                    <div class="flex items-center gap-4 border-t {{ $style['border'] ?? 'border-gray-100' }} pt-6">
                        <div class="w-12 h-12 bg-gradient-to-br {{ $style['avatar'] }} rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">{{ strtoupper(substr($testi->nama, 0, 1)) }}</div>
                        <div>
                            <h4 class="font-black {{ $style['name'] }}">{{ $testi->nama }}</h4>
                            <p class="text-xs font-bold tracking-widest {{ $style['bg'] == 'bg-gray-900' ? 'text-gray-400' : 'text-emerald-500' }} uppercase mb-1.5">{{ $testi->asal_kota ?? 'Indonesia' }}</p>
                            @if($testi->pesanan && $testi->pesanan->komunitas)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-600 rounded-md text-[10px] font-bold border border-gray-200">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Trip by {{ $testi->pesanan->komunitas->nama_komunitas }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Testi 1 -->
                <div data-aos="fade-up" data-aos-delay="0" class="bg-white p-10 rounded-[2rem] border border-gray-100 shadow-lg hover:shadow-2xl transition duration-300 relative group">
                    <div class="text-emerald-500 mb-6 flex gap-1 transform group-hover:scale-110 transition origin-left">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-gray-700 mb-8 italic text-lg font-medium leading-relaxed">"Pelayanan sangat profesional! Driver ramah dan sangat hafal medan. Sunrise di Sikunir adalah pengalaman tak terlupakan. Sangat direkomendasikan!"</p>
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">B</div>
                        <div>
                            <h4 class="font-black text-gray-900">Budi Santoso</h4>
                            <p class="text-xs font-bold tracking-widest text-emerald-500 uppercase">Jakarta</p>
                        </div>
                    </div>
                </div>

                <!-- Testi 2 -->
                <div data-aos="fade-up" data-aos-delay="150" class="bg-gray-900 p-10 rounded-[2rem] shadow-2xl transform md:-translate-y-4 relative group">
                    <div class="text-yellow-400 mb-6 flex gap-1 transform group-hover:scale-110 transition origin-left">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-gray-300 mb-8 italic text-lg font-medium leading-relaxed">"Pemesanan sangat mudah lewat website. Tinggal pesan, bayar, dan driver sudah standby di homestay. Prosesnya sangat transparan dan luar biasa!"</p>
                    <div class="flex items-center gap-4 border-t border-gray-700 pt-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">R</div>
                        <div>
                            <h4 class="font-black text-white">Rina Amelia</h4>
                            <p class="text-xs font-bold tracking-widest text-gray-400 uppercase">Surabaya</p>
                        </div>
                    </div>
                </div>

                <!-- Testi 3 -->
                <div data-aos="fade-up" data-aos-delay="300" class="bg-white p-10 rounded-[2rem] border border-gray-100 shadow-lg hover:shadow-2xl transition duration-300 relative group">
                    <div class="text-emerald-500 mb-6 flex gap-1 transform group-hover:scale-110 transition origin-left">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-gray-700 mb-8 italic text-lg font-medium leading-relaxed">"Harga bersaing dan pelayanan mantap. Armada Jeep-nya bersih dan terawat dengan baik. Bakal kembali pesen di sini kalau liburan ke Dieng lagi!"</p>
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">D</div>
                        <div>
                            <h4 class="font-black text-gray-900">Deni Setiawan</h4>
                            <p class="text-xs font-bold tracking-widest text-emerald-500 uppercase">Semarang</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-24 bg-gray-50 border-y border-gray-100 relative overflow-hidden">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16" data-aos="fade-down">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Pertanyaan Populer (FAQ)</h2>
            <p class="text-gray-500 text-lg">Jawaban cepat untuk pertanyaan yang sering diajukan calon wisatawan.</p>
        </div>

        <div class="space-y-4" x-data="{ active: null }" data-aos="fade-up">
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:border-emerald-300 transition-colors">
                <button @click="active !== 1 ? active = 1 : active = null" class="w-full px-6 py-5 flex justify-between items-center text-left focus:outline-none">
                    <span class="font-bold text-gray-900 text-lg">Satu armada Jeep muat berapa orang?</span>
                    <svg class="w-6 h-6 text-emerald-500 transform transition-transform duration-300" :class="{ 'rotate-180': active === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 1" x-collapse>
                    <div class="px-6 pb-6 text-gray-600 text-base leading-relaxed border-t border-gray-100 pt-4">
                        Satu armada Jeep memiliki kapasitas maksimal hingga 5-6 orang dewasa (termasuk penumpang di kursi depan). Kami sangat mengutamakan kenyamanan dan keselamatan Anda saat melintasi medan tanjakan Dieng.
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:border-emerald-300 transition-colors">
                <button @click="active !== 2 ? active = 2 : active = null" class="w-full px-6 py-5 flex justify-between items-center text-left focus:outline-none">
                    <span class="font-bold text-gray-900 text-lg">Apakah tiket wisata sudah termasuk dalam paket?</span>
                    <svg class="w-6 h-6 text-emerald-500 transform transition-transform duration-300" :class="{ 'rotate-180': active === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 2" x-collapse>
                    <div class="px-6 pb-6 text-gray-600 text-base leading-relaxed border-t border-gray-100 pt-4">
                        Secara umum, harga paket yang tertera <strong>hanya biaya sewa Jeep beserta BBM dan jasa driver</strong>. Harga belum termasuk retribusi/tiket masuk ke masing-masing objek wisata (seperti tiket Sikunir atau Kawah Sikidang). Pembayaran tiket wisata dibayar langsung di loket lokasi.
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:border-emerald-300 transition-colors">
                <button @click="active !== 3 ? active = 3 : active = null" class="w-full px-6 py-5 flex justify-between items-center text-left focus:outline-none">
                    <span class="font-bold text-gray-900 text-lg">Di mana lokasi titik kumpul (Meeting Point)?</span>
                    <svg class="w-6 h-6 text-emerald-500 transform transition-transform duration-300" :class="{ 'rotate-180': active === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 3" x-collapse>
                    <div class="px-6 pb-6 text-gray-600 text-base leading-relaxed border-t border-gray-100 pt-4">
                        Meeting point default berada di area Terminal/Basecamp Dieng atau homestay tempat Anda menginap (di area sekitar Dieng). Anda dapat menuliskan detail alamat penjemputan di kolom catatan saat melakukan pemesanan (booking).
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:border-emerald-300 transition-colors">
                <button @click="active !== 4 ? active = 4 : active = null" class="w-full px-6 py-5 flex justify-between items-center text-left focus:outline-none">
                    <span class="font-bold text-gray-900 text-lg">Bagaimana jika cuaca buruk / badai?</span>
                    <svg class="w-6 h-6 text-emerald-500 transform transition-transform duration-300" :class="{ 'rotate-180': active === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 4" x-collapse>
                    <div class="px-6 pb-6 text-gray-600 text-base leading-relaxed border-t border-gray-100 pt-4">
                        Keselamatan Anda adalah prioritas utama kami. Jika cuaca dinilai sangat ekstrem dan membahayakan oleh tim pengelola lapangan, perjalanan dapat dijadwal ulang (reschedule) atau dana dapat dikembalikan (refund) sesuai kesepakatan bersama.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CALL TO ACTION (CTA) RAKSASA BAWAH -->
<section class="relative py-24 md:py-32 bg-emerald-600 overflow-hidden">
    <!-- Ornamen Background -->
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-400 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-teal-800 rounded-full blur-3xl opacity-50"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center" data-aos="zoom-in" data-aos-duration="1000">
        <span class="text-emerald-100 font-black tracking-widest uppercase text-sm mb-4 block">Jangan Tunggu Nanti</span>
        <h2 class="text-4xl md:text-6xl font-black text-white mb-6 drop-shadow-md">Siap Memulai Petualangan?</h2>
        <p class="text-emerald-50 text-lg md:text-xl mb-12 max-w-2xl mx-auto leading-relaxed">
            Amankan Jeep Anda sekarang juga dan persiapkan diri untuk pengalaman liburan di Dataran Tinggi Dieng yang tak akan pernah Anda lupakan.
        </p>
        <a href="{{ route('paket') }}" class="inline-flex items-center justify-center px-12 py-5 bg-gray-900 text-white font-black text-xl md:text-2xl rounded-2xl shadow-2xl hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.4)] group gap-3">
            Pesan Jeep Sekarang
            <svg class="w-6 h-6 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>
</section>

<!-- Inisiasi AOS Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            once: true, // Animasi hanya terjadi sekali saat scroll ke bawah
            offset: 120, // Jarak memicu animasi
        });
    });
</script>

@endsection