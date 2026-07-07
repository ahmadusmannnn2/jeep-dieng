<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))</title>
    
    @if(isset($pengaturan_website) && $pengaturan_website->logo)
        <link rel="icon" href="{{ asset('storage/' . $pengaturan_website->logo) }}" type="image/png">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Menyembunyikan Scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    @php
        // Notifikasi Admin
        $pesananPendingCount = \App\Models\Pesanan::where('status', 'Pending')->count();
        $pesananNeedJeepCount = \App\Models\Pesanan::where('status', 'DP Lunas')->doesntHave('armadas')->count();
        $adminNotifCount = $pesananPendingCount + $pesananNeedJeepCount;

        // Notifikasi Pengelola
        $pengelolaNotifCount = 0;
        $pengelolaOrders = collect();
        if (Auth::user()->role === 'pengelola') {
            $pengelolaOrders = \App\Models\Pesanan::where('status', 'DP Lunas')
                ->where('komunitas_id', Auth::user()->komunitas_id)
                ->latest()
                ->get();
            $pengelolaNotifCount = $pengelolaOrders->count();
        }
    @endphp

    <div x-data="{ mobileSidebarOpen: false, sidebarCollapsed: false }" class="flex h-screen overflow-hidden">
        
        <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 bg-gray-900/80 z-40 md:hidden" style="display: none;"></div>

        <aside :class="(mobileSidebarOpen ? 'translate-x-0 ' : '-translate-x-full ') + (sidebarCollapsed ? 'md:w-20 ' : 'md:w-64 ')" class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white flex flex-col transition-all duration-300 ease-in-out md:relative md:translate-x-0 shrink-0">
            
            <div class="h-20 flex items-center justify-between md:justify-start px-5 border-b border-gray-800 gap-3 shrink-0">
                @if(isset($pengaturan_website) && $pengaturan_website->logo)
                    <img src="{{ asset('storage/' . $pengaturan_website->logo) }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain bg-white p-0.5 shrink-0 shadow-lg shadow-emerald-500/20">
                @else
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                @endif
                <h1 x-show="!sidebarCollapsed" class="text-xl font-bold text-emerald-500 tracking-wider truncate uppercase transition-opacity duration-300">
                    {{ $pengaturan_website->nama_website ?? 'JEEP DIENG' }}
                </h1>
                <button @click="mobileSidebarOpen = false" class="md:hidden text-gray-400 hover:text-white focus:outline-none ml-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto no-scrollbar px-4 py-6 space-y-2">
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'text-gray-400 hover:text-emerald-400 hover:bg-gray-800' }} rounded-xl transition overflow-hidden">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span x-show="!sidebarCollapsed" class="font-medium whitespace-nowrap">Dashboard</span>
                </a>
                @endif

                <a href="{{ route('admin.pesanan.index') }}" title="Pesanan Masuk" class="flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.pesanan.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'text-gray-400 hover:text-emerald-400 hover:bg-gray-800' }} rounded-xl transition overflow-hidden">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium whitespace-nowrap">Pesanan Masuk</span>
                    </div>
                    @if($pesananPendingCount > 0)
                        <span x-show="!sidebarCollapsed" class="relative flex h-3 w-3 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                    @endif
                </a>

                @if(Auth::user()->role === 'admin')
                <!-- Master Armada -->
                <div x-data="{ open: {{ request()->routeIs('admin.komunitas.*') || request()->routeIs('admin.jeep.*') || request()->routeIs('admin.supir.*') ? 'true' : 'false' }} }">
                    <button @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open }" type="button" title="Mitra Komunitas & Armada" class="w-full flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.komunitas.*') || request()->routeIs('admin.jeep.*') || request()->routeIs('admin.supir.*') ? 'text-white' : 'text-gray-400' }} hover:text-emerald-400 hover:bg-gray-800 rounded-xl transition overflow-hidden">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span x-show="!sidebarCollapsed" class="font-medium whitespace-nowrap">Mitra Komunitas</span>
                        </div>
                        <svg x-show="!sidebarCollapsed" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open && !sidebarCollapsed" style="display: none;" class="relative pl-12 pr-4 py-2 space-y-1 mt-1">
                        <div class="absolute left-[31px] top-0 bottom-4 w-px bg-gray-800"></div>
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.komunitas.index') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.komunitas.*') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.komunitas.*') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.komunitas.*') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Kelola Komunitas
                        </a>
                        @endif
                        <a href="{{ route('admin.jeep.index') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.jeep.*') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.jeep.*') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.jeep.*') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Data Kendaraan (Jeep)
                        </a>
                        <a href="{{ route('admin.supir.index') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.supir.*') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.supir.*') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.supir.*') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Daftar Supir Aktif
                        </a>
                    </div>
                </div>
                @endif

                @if(Auth::user()->role === 'admin')
                <!-- Katalog Wisata -->
                <div x-data="{ open: {{ request()->routeIs('admin.paket-wisata.*') || request()->routeIs('admin.rute-wisata.*') || request()->routeIs('admin.jadwal.*') ? 'true' : 'false' }} }">
                    <button @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open }" type="button" title="Paket Tour & Rute" class="w-full flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.paket-wisata.*') || request()->routeIs('admin.rute-wisata.*') || request()->routeIs('admin.jadwal.*') ? 'text-white' : 'text-gray-400' }} hover:text-emerald-400 hover:bg-gray-800 rounded-xl transition overflow-hidden">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span x-show="!sidebarCollapsed" class="font-medium whitespace-nowrap">Paket Tour & Rute</span>
                        </div>
                        <svg x-show="!sidebarCollapsed" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open && !sidebarCollapsed" style="display: none;" class="relative pl-12 pr-4 py-2 space-y-1 mt-1">
                        <div class="absolute left-[31px] top-0 bottom-4 w-px bg-gray-800"></div>
                        <a href="{{ route('admin.paket-wisata.index') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.paket-wisata.*') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.paket-wisata.*') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.paket-wisata.*') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Paket Wisata Utama
                        </a>
                        <a href="{{ route('admin.rute-wisata.index') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.rute-wisata.*') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.rute-wisata.*') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.rute-wisata.*') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Kelola Rute Destinasi
                        </a>
                        {{--
                        <a href="{{ route('admin.jadwal.index') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.jadwal.*') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.jadwal.*') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.jadwal.*') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Jadwal Keberangkatan
                        </a>
                        --}}
                    </div>
                </div>
                @endif

                <!-- Konten Website -->
                @if(Auth::user()->role === 'admin')
                <div x-data="{ open: {{ request()->routeIs('admin.konten-informasi.*') || request()->routeIs('admin.testimoni.*') ? 'true' : 'false' }} }">
                    <button @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open }" type="button" title="Konten & Informasi" class="w-full flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.konten-informasi.*') || request()->routeIs('admin.testimoni.*') ? 'text-white' : 'text-gray-400' }} hover:text-emerald-400 hover:bg-gray-800 rounded-xl transition overflow-hidden">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            <span x-show="!sidebarCollapsed" class="font-medium whitespace-nowrap">Konten & Informasi</span>
                        </div>
                        <svg x-show="!sidebarCollapsed" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open && !sidebarCollapsed" style="display: none;" class="relative pl-12 pr-4 py-2 space-y-1 mt-1">
                        <div class="absolute left-[31px] top-0 bottom-4 w-px bg-gray-800"></div>
                        <a href="{{ route('admin.konten-informasi.index') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.konten-informasi.*') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.konten-informasi.*') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.konten-informasi.*') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Artikel & Promo
                        </a>
                        <a href="{{ route('admin.testimoni.index') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.testimoni.*') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.testimoni.*') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.testimoni.*') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Review Pelanggan
                        </a>
                    </div>
                </div>
                @endif

                @if(Auth::user()->role === 'pengelola')
                <!-- Review Pelanggan (Pengelola) -->
                <div class="px-3">
                    <a href="{{ route('admin.testimoni.index') }}" title="Review Pelanggan" class="w-full flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.testimoni.*') ? 'text-white bg-gray-800' : 'text-gray-400' }} hover:text-emerald-400 hover:bg-gray-800 rounded-xl transition overflow-hidden">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            <span x-show="!sidebarCollapsed" class="font-medium whitespace-nowrap">Review Pelanggan</span>
                        </div>
                    </a>
                </div>
                @endif

                @if(Auth::user()->role === 'admin')
                <!-- Laporan -->
                <div x-data="{ open: {{ request()->routeIs('admin.laporan.*') ? 'true' : 'false' }} }">
                    <button @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open }" type="button" title="Keuangan & Omzet" class="w-full flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.laporan.*') ? 'text-white' : 'text-gray-400' }} hover:text-emerald-400 hover:bg-gray-800 rounded-xl transition overflow-hidden">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span x-show="!sidebarCollapsed" class="font-medium whitespace-nowrap">Keuangan & Omzet</span>
                        </div>
                        <svg x-show="!sidebarCollapsed" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open && !sidebarCollapsed" style="display: none;" class="relative pl-12 pr-4 py-2 space-y-1 mt-1">
                        <div class="absolute left-[31px] top-0 bottom-4 w-px bg-gray-800"></div>
                        <a href="{{ route('admin.laporan.index') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.laporan.index') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.laporan.index') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.laporan.index') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Rekap Transaksi Masuk
                        </a>
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.laporan.komunitas') }}" class="flex items-center relative py-2 px-3 rounded-xl text-sm {{ request()->routeIs('admin.laporan.komunitas') ? 'text-emerald-400 font-bold bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition-all">
                            <span class="absolute -left-[17px] w-3 h-px {{ request()->routeIs('admin.laporan.komunitas') ? 'bg-emerald-400' : 'bg-gray-800' }}"></span>
                            <span class="absolute -left-[19px] w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.laporan.komunitas') ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-transparent' }}"></span>
                            Laporan Komunitas
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-800 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition overflow-hidden">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-y-auto w-full relative bg-gray-50">
            
            <header class="h-20 shrink-0 bg-white shadow-sm flex items-center justify-between px-4 md:px-8 z-30 sticky top-0 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <button @click="window.innerWidth < 768 ? mobileSidebarOpen = true : sidebarCollapsed = !sidebarCollapsed" class="p-2 text-gray-500 hover:text-emerald-500 focus:outline-none transition bg-gray-50 rounded-lg border border-gray-100 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">@yield('header_title')</h2>
                        <p class="text-xs md:text-sm text-gray-500 hidden sm:block">@yield('header_subtitle')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    
                    <!-- Lonceng Notifikasi Internal -->
                    <div x-data="{ showInternalNotif: false }" class="relative">
                        <button @click="showInternalNotif = !showInternalNotif" @click.away="showInternalNotif = false" class="relative p-2.5 text-gray-500 hover:text-emerald-600 bg-gray-50 hover:bg-emerald-50 rounded-full transition border border-gray-100 shadow-sm shrink-0 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @if( (Auth::user()->role === 'admin' && $adminNotifCount > 0) || (Auth::user()->role === 'pengelola' && $pengelolaNotifCount > 0) )
                                <span class="absolute top-0 right-0 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                                </span>
                            @endif
                        </button>
                        
                        <div x-show="showInternalNotif" x-transition x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50">
                                <h3 class="text-sm font-bold text-gray-900">Pemberitahuan Sistem</h3>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @if(Auth::user()->role === 'admin')
                                    @if($adminNotifCount == 0)
                                        <div class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada tugas baru.</div>
                                    @else
                                        @if($pesananPendingCount > 0)
                                            <a href="{{ route('admin.pesanan.index') }}" class="block px-4 py-4 border-b border-gray-50 hover:bg-emerald-50 transition">
                                                <p class="text-sm text-gray-800 font-medium">Terdapat <strong>{{ $pesananPendingCount }} pesanan</strong> menunggu verifikasi pembayaran.</p>
                                            </a>
                                        @endif
                                        @if($pesananNeedJeepCount > 0)
                                            <a href="{{ route('admin.pesanan.index') }}" class="block px-4 py-4 border-b border-gray-50 hover:bg-emerald-50 transition">
                                                <p class="text-sm text-gray-800 font-medium text-amber-600">Ada <strong>{{ $pesananNeedJeepCount }} pesanan</strong> Lunas DP yang belum mendapatkan penugasan Jeep!</p>
                                            </a>
                                        @endif
                                    @endif
                                @elseif(Auth::user()->role === 'pengelola')
                                    @forelse($pengelolaOrders as $order)
                                        <a href="{{ route('admin.pesanan.show', $order->id) }}" class="block px-4 py-4 border-b border-gray-50 hover:bg-emerald-50 transition">
                                            <p class="text-sm text-gray-800 font-medium">Penugasan baru untuk tiket <strong>#{{ $order->id }}</strong>. Segera cek dan siapkan armada!</p>
                                            <p class="text-xs text-emerald-600 font-bold mt-1">Klik untuk mencetak Surat Jalan</p>
                                        </a>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada penugasan baru.</div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('home') }}" target="_blank" title="Pratinjau Halaman Pengunjung" class="p-2.5 text-gray-500 hover:text-emerald-600 bg-gray-50 hover:bg-emerald-50 rounded-full transition border border-gray-100 shadow-sm shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </a>

                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.pengaturan.index') }}" title="Pengaturan Website" class="p-2.5 text-gray-500 hover:text-emerald-600 bg-gray-50 hover:bg-emerald-50 rounded-full transition border border-gray-100 shadow-sm shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </a>
                    @endif

                    <div class="text-right hidden sm:block border-l border-gray-100 pl-4 ml-1 shrink-0">
                        <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-emerald-600 font-medium bg-emerald-50 px-2 py-0.5 rounded inline-block mt-0.5">
                            @if(Auth::user()->role === 'pengelola')
                                Pengelola ({{ Auth::user()->komunitas->nama_komunitas ?? '-' }})
                            @else
                                Administrator
                            @endif
                        </p>
                    </div>
                    @if(Auth::user()->foto_profil)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->foto_profil) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover shadow-md border-2 border-emerald-100 shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold shadow-md border-2 border-emerald-100 shrink-0">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </header>

            <div class="p-4 md:p-8 space-y-6">
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl shadow-sm mb-6 flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="font-bold text-sm">Berhasil!</p>
                            <p class="text-sm mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
