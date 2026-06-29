<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Autentikasi - ' . ($pengaturan_website->nama_website ?? config('app.name', 'Jeep Dieng')))</title>
    
    @if(isset($pengaturan_website) && $pengaturan_website->logo)
        <link rel="icon" href="{{ asset('storage/' . $pengaturan_website->logo) }}" type="image/png">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="font-sans text-gray-900 antialiased flex min-h-screen bg-gray-50 overflow-hidden">
    
    <!-- SISI KIRI: SLIDESHOW GAMBAR JEEP (Hanya tampil di md ke atas) -->
    <div class="hidden md:flex md:w-1/2 lg:w-3/5 bg-gray-950 relative overflow-hidden shrink-0">
        <!-- Slideshow Container -->
        <div class="absolute inset-0 z-0">
            @php
                // Cari gambar slideshow dari setting atau gunakan default bertema jeep
                $slides = $pengaturan_website->login_images ?? [];
                if (empty($slides)) {
                    $slides = [
                        'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=1200',
                        'https://images.unsplash.com/photo-1539799139360-4f43b5937ee8?auto=format&fit=crop&q=80&w=1200',
                        'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=1200'
                    ];
                }
            @endphp
            
            <div x-data="{ 
                activeSlide: 0, 
                slides: {{ json_encode(array_map(fn($s) => str_starts_with($s, 'http') ? $s : asset('storage/' . $s), $slides)) }},
                init() {
                    setInterval(() => {
                        this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                    }, 5000);
                }
            }" class="w-full h-full relative">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index" 
                         x-transition:enter="transition ease-out duration-1000"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-1000"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0">
                        <img :src="slide" class="w-full h-full object-cover brightness-[0.35]" alt="Jeep Adventure">
                    </div>
                </template>
            </div>
        </div>

        <!-- Overlay Text & Logo -->
        <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/40 to-transparent z-10 p-12 lg:p-16 flex flex-col justify-between">
            <a href="/" class="flex items-center gap-3">
                @if(isset($pengaturan_website) && $pengaturan_website->logo)
                    <img src="{{ asset('storage/' . $pengaturan_website->logo) }}" alt="Logo" class="w-10 h-10 rounded-xl object-contain bg-white p-0.5 shadow-lg">
                @else
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                @endif
                <span class="text-2xl font-extrabold text-white tracking-widest uppercase">{{ $pengaturan_website->nama_website ?? 'JEEP DIENG' }}</span>
            </a>

            <div>
                <span class="px-3 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-full uppercase tracking-wider">Premium Adventure</span>
                <h2 class="text-4xl lg:text-5xl font-black text-white mt-4 leading-tight">Jelajahi Negeri di Atas Awan</h2>
                <p class="text-gray-300 mt-4 text-sm lg:text-base max-w-md">Rasakan petualangan tak terlupakan menyusuri keindahan Dataran Tinggi Dieng dengan layanan Jeep terpercaya kami.</p>
            </div>

            <p class="text-gray-500 text-xs font-medium">© {{ date('Y') }} {{ $pengaturan_website->nama_website ?? 'Jeep Dieng' }}. All rights reserved.</p>
        </div>
    </div>

    <!-- SISI KANAN: FORM LOGIN / REGISTER -->
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 md:px-12 lg:px-16 bg-gray-50 relative overflow-y-auto max-h-screen">
        <div class="absolute top-[-10%] left-[-10%] w-80 h-80 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-80 h-80 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>

        <div class="w-full max-w-md bg-white p-8 md:p-10 shadow-xl shadow-emerald-500/5 border border-gray-100 rounded-3xl relative z-10">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-t-3xl"></div>
            
            <!-- Logo Mobile -->
            <div class="flex justify-center md:hidden mb-6">
                <a href="/" class="flex items-center gap-2">
                    @if(isset($pengaturan_website) && $pengaturan_website->logo)
                        <img src="{{ asset('storage/' . $pengaturan_website->logo) }}" alt="Logo" class="w-10 h-10 rounded-xl object-contain bg-emerald-500 p-0.5 shadow-lg">
                    @else
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                    @endif
                    <span class="text-xl font-bold text-gray-900 uppercase tracking-tight">{{ $pengaturan_website->nama_website ?? 'JEEP DIENG' }}</span>
                </a>
            </div>

            {{ $slot }}
        </div>
    </div>
</body>
</html>