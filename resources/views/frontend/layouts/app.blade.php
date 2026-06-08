<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($pengaturan_website->nama_website ?? 'Jeep Dieng'))</title>
    
    @if(isset($pengaturan_website) && $pengaturan_website->logo)
        <link rel="icon" href="{{ asset('storage/' . $pengaturan_website->logo) }}" type="image/png">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Safe area padding untuk mobile browser modern */
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
        /* Sembunyikan scrollbar bawaan */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen pb-20 md:pb-0">

    <nav class="bg-white/90 backdrop-blur-md fixed top-0 w-full z-50 shadow-sm border-b border-gray-100 transition-all h-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="flex justify-between items-center h-full">
                
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    @if(isset($pengaturan_website) && $pengaturan_website->logo)
                        <img src="{{ asset('storage/' . $pengaturan_website->logo) }}" alt="Logo" class="w-10 h-10 rounded-xl object-contain bg-emerald-500 p-1 shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                    @endif
                    <span class="text-2xl font-extrabold text-gray-900 tracking-tight uppercase">{{ $pengaturan_website->nama_website ?? 'JEEP DIENG' }}</span>
                </a>

                <div class="hidden lg:flex items-center gap-1">
                    
                    <a href="{{ route('home') }}" class="group relative flex items-center gap-2 px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-emerald-50 {{ request()->routeIs('home') ? 'bg-emerald-50' : '' }}">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-1 group-hover:scale-110 {{ request()->routeIs('home') ? 'text-emerald-500 -translate-y-1 scale-110' : 'text-gray-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="font-bold text-sm transition-colors duration-300 {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-gray-600 group-hover:text-emerald-600' }}">Beranda</span>
                    </a>

                    <a href="{{ route('paket') }}" class="group relative flex items-center gap-2 px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-emerald-50 {{ request()->routeIs('paket') ? 'bg-emerald-50' : '' }}">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-1 group-hover:scale-110 {{ request()->routeIs('paket') ? 'text-emerald-500 -translate-y-1 scale-110' : 'text-gray-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-sm transition-colors duration-300 {{ request()->routeIs('paket') ? 'text-emerald-600' : 'text-gray-600 group-hover:text-emerald-600' }}">Paket Wisata</span>
                    </a>

                    <a href="{{ route('rute') }}" class="group relative flex items-center gap-2 px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-emerald-50 {{ request()->routeIs('rute') ? 'bg-emerald-50' : '' }}">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-1 group-hover:scale-110 {{ request()->routeIs('rute') ? 'text-emerald-500 -translate-y-1 scale-110' : 'text-gray-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        <span class="font-bold text-sm transition-colors duration-300 {{ request()->routeIs('rute') ? 'text-emerald-600' : 'text-gray-600 group-hover:text-emerald-600' }}">Rute Trip</span>
                    </a>

                    <a href="{{ route('promo') }}" class="group relative flex items-center gap-2 px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-emerald-50 {{ request()->routeIs('promo') ? 'bg-emerald-50' : '' }}">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-1 group-hover:scale-110 {{ request()->routeIs('promo') ? 'text-emerald-500 -translate-y-1 scale-110' : 'text-gray-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        <span class="font-bold text-sm transition-colors duration-300 {{ request()->routeIs('promo') ? 'text-emerald-600' : 'text-gray-600 group-hover:text-emerald-600' }}">Info & Promo</span>
                    </a>

                    @auth
                        @if(Auth::user()->role !== 'admin')
                            <a href="{{ route('dashboard') }}" class="group relative flex items-center gap-2 px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-emerald-50 {{ request()->routeIs('dashboard') ? 'bg-emerald-50' : '' }}">
                                <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-1 group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-emerald-500 -translate-y-1 scale-110' : 'text-gray-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span class="font-bold text-sm transition-colors duration-300 {{ request()->routeIs('dashboard') ? 'text-emerald-600' : 'text-gray-600 group-hover:text-emerald-600' }}">Riwayat Pesanan</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="group relative flex items-center gap-2 px-3 py-2.5 rounded-xl transition-all duration-300 hover:bg-emerald-50 {{ request()->routeIs('profile.edit') ? 'bg-emerald-50' : '' }}">
                                <svg class="w-5 h-5 transition-transform duration-300 group-hover:-translate-y-1 group-hover:scale-110 {{ request()->routeIs('profile.edit') ? 'text-emerald-500 -translate-y-1 scale-110' : 'text-gray-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="font-bold text-sm transition-colors duration-300 {{ request()->routeIs('profile.edit') ? 'text-emerald-600' : 'text-gray-600 group-hover:text-emerald-600' }}">Profil Akun</span>
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="hidden lg:flex items-center gap-4">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-emerald-500 transition shadow-lg hover:shadow-emerald-500/30 flex items-center gap-2 group">
                                Panel Admin <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @endif

                        @php
                            $avatarUrl = isset(Auth::user()->foto_profil) && Auth::user()->foto_profil 
                                        ? asset('storage/' . Auth::user()->foto_profil) 
                                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=10b981&color=fff&bold=true';
                        @endphp
                        
                        <div class="flex items-center gap-3 border-l border-gray-100 pl-4">
                            <img src="{{ $avatarUrl }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border-2 border-emerald-500 shadow-sm shrink-0">
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" title="Keluar Akun" class="flex items-center justify-center w-10 h-10 bg-red-50 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm group">
                                    <svg class="w-5 h-5 transform group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 font-bold hover:text-emerald-500 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-emerald-500 transition shadow-lg hover:shadow-emerald-500/30">Daftar Member</a>
                    @endauth
                </div>

                </div>
        </div>
    </nav>

    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 py-12 border-t border-gray-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <span class="text-2xl font-extrabold text-white tracking-tight mb-4 block uppercase">{{ $pengaturan_website->nama_website ?? 'JEEP DIENG' }}</span>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">{{ $pengaturan_website->deskripsi_footer ?? 'Platform penyewaan Jeep resmi dan terpercaya.' }}</p>
                <p class="text-gray-500 text-xs italic">{{ $pengaturan_website->alamat ?? 'Wonosobo, Jawa Tengah' }}</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">Akses Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('paket') }}" class="hover:text-emerald-400 transition">Paket Wisata</a></li>
                    <li><a href="{{ route('rute') }}" class="hover:text-emerald-400 transition">Destinasi & Rute</a></li>
                    <li><a href="{{ route('promo') }}" class="hover:text-emerald-400 transition">Promo Terbaru</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-emerald-400 transition">Daftar Member</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">Pusat Bantuan</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> {{ $pengaturan_website->no_telp ?? '0812-3456-7890' }}</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> {{ $pengaturan_website->email ?? 'support@jeepdieng.com' }}</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-500 mb-10 md:mb-0">
            &copy; {{ date('Y') }} Sistem Manajemen {{ $pengaturan_website->nama_website ?? 'Jeep Dieng' }}. Dibuat dengan 💚.
        </div>
    </footer>

    <div class="lg:hidden fixed bottom-0 w-full bg-white/95 backdrop-blur-lg border-t border-gray-100 z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] pb-safe overflow-x-auto no-scrollbar">
        <div class="flex justify-between items-center h-16 px-1 w-full min-w-max">
            
            <a href="{{ route('home') }}" class="relative flex-1 flex flex-col items-center justify-center h-full text-gray-400 hover:text-emerald-500 transition-colors group px-2 {{ request()->routeIs('home') ? 'text-emerald-600' : '' }}">
                <div class="absolute inset-0 bg-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl m-1 {{ request()->routeIs('home') ? 'opacity-100' : '' }}"></div>
                <svg class="w-6 h-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-2 {{ request()->routeIs('home') ? '-translate-y-2 text-emerald-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="absolute bottom-2 text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none {{ request()->routeIs('home') ? 'opacity-100 text-emerald-600' : '' }}">Home</span>
            </a>

            <a href="{{ route('paket') }}" class="relative flex-1 flex flex-col items-center justify-center h-full text-gray-400 hover:text-emerald-500 transition-colors group px-2 {{ request()->routeIs('paket') ? 'text-emerald-600' : '' }}">
                <div class="absolute inset-0 bg-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl m-1 {{ request()->routeIs('paket') ? 'opacity-100' : '' }}"></div>
                <svg class="w-6 h-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-2 {{ request()->routeIs('paket') ? '-translate-y-2 text-emerald-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="absolute bottom-2 text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none {{ request()->routeIs('paket') ? 'opacity-100 text-emerald-600' : '' }}">Paket</span>
            </a>

            <a href="{{ route('rute') }}" class="relative flex-1 flex flex-col items-center justify-center h-full text-gray-400 hover:text-emerald-500 transition-colors group px-2 {{ request()->routeIs('rute') ? 'text-emerald-600' : '' }}">
                <div class="absolute inset-0 bg-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl m-1 {{ request()->routeIs('rute') ? 'opacity-100' : '' }}"></div>
                <svg class="w-6 h-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-2 {{ request()->routeIs('rute') ? '-translate-y-2 text-emerald-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                <span class="absolute bottom-2 text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none {{ request()->routeIs('rute') ? 'opacity-100 text-emerald-600' : '' }}">Rute</span>
            </a>

            <a href="{{ route('promo') }}" class="relative flex-1 flex flex-col items-center justify-center h-full text-gray-400 hover:text-emerald-500 transition-colors group px-2 {{ request()->routeIs('promo') ? 'text-emerald-600' : '' }}">
                <div class="absolute inset-0 bg-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl m-1 {{ request()->routeIs('promo') ? 'opacity-100' : '' }}"></div>
                <svg class="w-6 h-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-2 {{ request()->routeIs('promo') ? '-translate-y-2 text-emerald-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <span class="absolute bottom-2 text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none {{ request()->routeIs('promo') ? 'opacity-100 text-emerald-600' : '' }}">Promo</span>
            </a>

            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="relative flex-1 flex flex-col items-center justify-center h-full text-gray-400 hover:text-emerald-500 transition-colors group px-2">
                        <div class="absolute inset-0 bg-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl m-1"></div>
                        <svg class="w-6 h-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="absolute bottom-2 text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">Admin</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="relative flex-1 flex flex-col items-center justify-center h-full text-gray-400 hover:text-emerald-500 transition-colors group px-2 {{ request()->routeIs('dashboard') ? 'text-emerald-600' : '' }}">
                        <div class="absolute inset-0 bg-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl m-1 {{ request()->routeIs('dashboard') ? 'opacity-100' : '' }}"></div>
                        <svg class="w-6 h-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-2 {{ request()->routeIs('dashboard') ? '-translate-y-2 text-emerald-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="absolute bottom-2 text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none {{ request()->routeIs('dashboard') ? 'opacity-100 text-emerald-600' : '' }}">Riwayat</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="relative flex-1 flex flex-col items-center justify-center h-full transition-colors group px-2">
                        <div class="absolute inset-0 bg-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl m-1 {{ request()->routeIs('profile.edit') ? 'opacity-100' : '' }}"></div>
                        @php
                            $avatarUrlMobile = isset(Auth::user()->foto_profil) && Auth::user()->foto_profil 
                                        ? asset('storage/' . Auth::user()->foto_profil) 
                                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=10b981&color=fff&bold=true';
                        @endphp
                        <img src="{{ $avatarUrlMobile }}" alt="Profil" class="w-7 h-7 rounded-full object-cover relative z-10 transition-transform duration-300 group-hover:-translate-y-2 {{ request()->routeIs('profile.edit') ? '-translate-y-2 border-2 border-emerald-500' : 'border border-gray-300' }}">
                        <span class="absolute bottom-2 text-[10px] font-bold text-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none {{ request()->routeIs('profile.edit') ? 'opacity-100 text-emerald-600' : '' }}">Profil</span>
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="relative flex-1 flex flex-col items-center justify-center h-full text-gray-400 hover:text-emerald-500 transition-colors group px-2">
                    <div class="absolute inset-0 bg-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl m-1"></div>
                    <svg class="w-6 h-6 relative z-10 transition-transform duration-300 group-hover:-translate-y-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span class="absolute bottom-2 text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">Masuk</span>
                </a>
            @endauth

        </div>
    </div>

</body>
</html>