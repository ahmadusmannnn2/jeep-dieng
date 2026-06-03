<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jeep Dieng - Jelajahi Keindahan Alam Dieng')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <nav x-data="{ mobileMenuOpen: false }" class="bg-white/90 backdrop-blur-md fixed w-full z-50 shadow-sm border-b border-gray-100 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <span class="text-2xl font-extrabold text-gray-900 tracking-tight">JEEP<span class="text-emerald-500">DIENG</span></span>
                </a>

                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-emerald-600 font-bold' : 'text-gray-600 hover:text-emerald-500 font-semibold' }} transition">Beranda</a>
                    <a href="{{ route('paket') }}" class="{{ request()->routeIs('paket') ? 'text-emerald-600 font-bold' : 'text-gray-600 hover:text-emerald-500 font-semibold' }} transition">Paket Wisata</a>
                    <a href="{{ route('rute') }}" class="{{ request()->routeIs('rute') ? 'text-emerald-600 font-bold' : 'text-gray-600 hover:text-emerald-500 font-semibold' }} transition">Rute Trip</a>
                    <a href="{{ route('promo') }}" class="{{ request()->routeIs('promo') ? 'text-emerald-600 font-bold' : 'text-gray-600 hover:text-emerald-500 font-semibold' }} transition">Info & Promo</a>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    @auth
                        @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin_komunitas')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 font-bold hover:text-emerald-500 transition">Panel Admin</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-emerald-600 font-bold' : 'text-gray-600 font-bold hover:text-emerald-500' }} transition">Riwayat Pesanan</a>
                            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'text-emerald-600 font-bold' : 'text-gray-600 font-bold hover:text-emerald-500' }} transition">Profil</a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 transition">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 font-semibold hover:text-emerald-500 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gray-900 text-white font-semibold rounded-xl hover:bg-emerald-500 transition shadow-lg hover:shadow-emerald-500/30">Daftar</a>
                    @endauth
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-emerald-500 focus:outline-none">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" class="md:hidden bg-white border-t border-gray-100 shadow-xl" style="display: none;">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-3 rounded-xl text-base font-semibold {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-800 hover:bg-emerald-50 hover:text-emerald-500' }}">Beranda</a>
                <a href="{{ route('paket') }}" class="block px-3 py-3 rounded-xl text-base font-semibold {{ request()->routeIs('paket') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-800 hover:bg-emerald-50 hover:text-emerald-500' }}">Paket Wisata</a>
                <a href="{{ route('rute') }}" class="block px-3 py-3 rounded-xl text-base font-semibold {{ request()->routeIs('rute') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-800 hover:bg-emerald-50 hover:text-emerald-500' }}">Rute Trip</a>
                <a href="{{ route('promo') }}" class="block px-3 py-3 rounded-xl text-base font-semibold {{ request()->routeIs('promo') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-800 hover:bg-emerald-50 hover:text-emerald-500' }}">Info & Promo</a>
                <hr class="border-gray-100 my-2">
                @auth
                    @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin_komunitas')
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-gray-800 hover:bg-emerald-50 hover:text-emerald-500 mb-1">Panel Admin</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-800 hover:bg-emerald-50 hover:text-emerald-500' }} mb-1">Riwayat Pesanan</a>
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('profile.edit') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-800 hover:bg-emerald-50 hover:text-emerald-500' }} mb-1">Profil</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-3 rounded-xl text-base font-bold text-red-600 hover:bg-red-50 transition">Keluar Akun</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-3 rounded-xl text-base font-semibold text-gray-800 hover:bg-gray-50">Masuk</a>
                    <a href="{{ route('register') }}" class="block px-3 py-3 rounded-xl text-base font-semibold text-white bg-gray-900 text-center mt-2">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 py-12 border-t border-gray-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <span class="text-2xl font-extrabold text-white tracking-tight mb-4 block">JEEP<span class="text-emerald-500">DIENG</span></span>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">Platform penyewaan Jeep resmi dan terpercaya di Dataran Tinggi Dieng. Menghubungkan Anda dengan komunitas Jeep lokal untuk pengalaman wisata tak terlupakan.</p>
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
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> 0812-3456-7890 (CS)</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> support@jeepdieng.com</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Sistem Penyewaan Jeep Dieng. Dibuat dengan 💚.
        </div>
    </footer>

</body>
</html>