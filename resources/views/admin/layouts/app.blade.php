<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Jeep Dieng')</title>
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
        // Menghitung jumlah pesanan baru (Pending) secara otomatis
        $pendingQuery = \App\Models\Pesanan::where('status', 'Pending');
        if (Auth::user()->role === 'admin_komunitas') {
            $pendingQuery->where('komunitas_id', Auth::user()->komunitas_id);
        }
        $pesananPendingCount = $pendingQuery->count();
    @endphp

    <div x-data="{ mobileSidebarOpen: false, desktopSidebarOpen: true }" class="flex h-screen overflow-hidden">
        
        <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 bg-gray-900/80 z-20 md:hidden" style="display: none;"></div>

        <aside x-show="desktopSidebarOpen" :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-white flex flex-col transition-transform duration-300 ease-in-out md:relative md:translate-x-0">
            
            <div class="h-20 flex items-center justify-between md:justify-start px-5 border-b border-gray-800 gap-3 shrink-0">
                @if(isset($pengaturan_website) && $pengaturan_website->logo)
                    <img src="{{ asset('storage/' . $pengaturan_website->logo) }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain bg-white p-0.5 shrink-0 shadow-lg shadow-emerald-500/20">
                @else
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                @endif
                <h1 class="text-xl font-bold text-emerald-500 tracking-wider truncate uppercase">
                    {{ $pengaturan_website->nama_website ?? 'JEEP DIENG' }}
                </h1>
                <button @click="mobileSidebarOpen = false" class="md:hidden text-gray-400 hover:text-white focus:outline-none ml-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto no-scrollbar px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'text-gray-400 hover:text-emerald-400 hover:bg-gray-800' }} rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('admin.pesanan.index') }}" class="flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.pesanan.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'text-gray-400 hover:text-emerald-400 hover:bg-gray-800' }} rounded-xl transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span class="font-medium">Pesanan Masuk</span>
                    </div>
                    @if($pesananPendingCount > 0)
                        <span class="relative flex h-3 w-3 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </span>
                    @endif
                </a>

                <!-- Master Armada -->
                <div x-data="{ open: {{ request()->routeIs('admin.jeep.*') || request()->routeIs('admin.supir.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.jeep.*') || request()->routeIs('admin.supir.*') ? 'text-white' : 'text-gray-400' }} hover:text-emerald-400 hover:bg-gray-800 rounded-xl transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="font-medium">Master Armada</span>
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" style="display: none;" class="pl-12 pr-4 py-1 space-y-1">
                        <a href="{{ route('admin.jeep.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.jeep.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-emerald-400' }} transition">Data Jeep</a>
                        <a href="{{ route('admin.supir.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.supir.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-emerald-400' }} transition">Data Supir</a>
                    </div>
                </div>

                <!-- Katalog Wisata -->
                <div x-data="{ open: {{ request()->routeIs('admin.paket-wisata.*') || request()->routeIs('admin.rute-wisata.*') || request()->routeIs('admin.jadwal.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.paket-wisata.*') || request()->routeIs('admin.rute-wisata.*') || request()->routeIs('admin.jadwal.*') ? 'text-white' : 'text-gray-400' }} hover:text-emerald-400 hover:bg-gray-800 rounded-xl transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium">Katalog Wisata</span>
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" style="display: none;" class="pl-12 pr-4 py-1 space-y-1">
                        <a href="{{ route('admin.paket-wisata.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.paket-wisata.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-emerald-400' }} transition">Paket Wisata</a>
                        <a href="{{ route('admin.rute-wisata.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.rute-wisata.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-emerald-400' }} transition">Rute Wisata</a>
                        <a href="{{ route('admin.jadwal.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.jadwal.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-emerald-400' }} transition">Jadwal Tour</a>
                    </div>
                </div>

                <!-- Konten Website -->
                <div x-data="{ open: {{ request()->routeIs('admin.konten-informasi.*') || request()->routeIs('admin.testimoni.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.konten-informasi.*') || request()->routeIs('admin.testimoni.*') ? 'text-white' : 'text-gray-400' }} hover:text-emerald-400 hover:bg-gray-800 rounded-xl transition">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            <span class="font-medium">Konten Website</span>
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" style="display: none;" class="pl-12 pr-4 py-1 space-y-1">
                        <a href="{{ route('admin.konten-informasi.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.konten-informasi.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-emerald-400' }} transition">Info & Promo</a>
                        <a href="{{ route('admin.testimoni.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.testimoni.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-emerald-400' }} transition">Testimoni</a>
                    </div>
                </div>

                <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.laporan.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'text-gray-400 hover:text-emerald-400 hover:bg-gray-800' }} rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="font-medium">Laporan Keuangan</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-800 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-y-auto w-full relative bg-gray-50">
            
            <header class="h-20 shrink-0 bg-white shadow-sm flex items-center justify-between px-4 md:px-8 z-10 sticky top-0 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <button @click="window.innerWidth < 768 ? mobileSidebarOpen = true : desktopSidebarOpen = !desktopSidebarOpen" class="p-2 text-gray-500 hover:text-emerald-500 focus:outline-none transition bg-gray-50 rounded-lg border border-gray-100 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">@yield('header_title')</h2>
                        <p class="text-xs md:text-sm text-gray-500 hidden sm:block">@yield('header_subtitle')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    
                    <a href="{{ route('home') }}" target="_blank" title="Pratinjau Halaman Pengunjung" class="p-2.5 text-gray-500 hover:text-emerald-600 bg-gray-50 hover:bg-emerald-50 rounded-full transition border border-gray-100 shadow-sm shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </a>

                    <a href="{{ route('admin.pengaturan.index') }}" title="Pengaturan Website" class="p-2.5 text-gray-500 hover:text-emerald-600 bg-gray-50 hover:bg-emerald-50 rounded-full transition border border-gray-100 shadow-sm shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </a>

                    <div class="text-right hidden sm:block border-l border-gray-100 pl-4 ml-1 shrink-0">
                        <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-emerald-600 font-medium bg-emerald-50 px-2 py-0.5 rounded inline-block mt-0.5">
                            Administrator
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold shadow-md shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
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