@extends('frontend.layouts.app')

@section('title', 'Jeep Dieng - Jelajahi Keindahan Alam Dieng')

@section('content')
<section class="relative w-full h-[85vh] min-h-[600px] flex items-center justify-center overflow-hidden">
    
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1519985176271-adb1088fa94c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Jeep Adventure" class="w-full h-full object-cover object-center" />
        
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 via-emerald-900/80 to-gray-900/70"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center mt-10">
        <span class="px-4 py-1.5 rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-sm tracking-widest uppercase mb-6 border border-emerald-500/30 backdrop-blur-md">
            Lebih dari 3 Komunitas Bergabung
        </span>
        
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white tracking-tight mb-6 leading-tight drop-shadow-lg">
            Jelajahi <span class="text-emerald-400">Keindahan Dieng</span><br>Tanpa Batas.
        </h1>
        
        <p class="text-lg md:text-xl text-gray-200 max-w-2xl mb-10 leading-relaxed drop-shadow-md">
            Pesan layanan Jeep tangguh untuk menaklukkan medan Dieng. Nikmati Golden Sunrise Sikunir dan Kawah Sikidang dengan aman dan nyaman bersama supir profesional kami.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('paket') }}" class="px-8 py-4 bg-emerald-500 text-white font-bold rounded-2xl hover:bg-emerald-400 transition shadow-[0_0_20px_rgba(16,185,129,0.4)] transform hover:-translate-y-1">
                Lihat Paket Wisata
            </a>
            <a href="{{ route('promo') }}" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl hover:bg-white/20 transition border border-white/20">
                Promo Terbaru
            </a>
        </div>
    </div>
    
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-10 translate-y-[1px]">
        <svg class="relative block w-full h-[60px] md:h-[100px] lg:h-[150px]" fill="#F9FAFB" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V120H0Z"></path>
        </svg>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Paket Populer</h2>
                <p class="mt-4 text-gray-500 max-w-2xl">Pilihan favorit wisatawan untuk menjelajahi pesona Dieng.</p>
            </div>
            <a href="{{ route('paket') }}" class="inline-flex items-center gap-2 text-emerald-600 font-bold hover:text-emerald-700 transition">
                Lihat Semua Paket <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($paket as $item)
                <div class="bg-white rounded-3xl p-8 border border-gray-100 hover:border-emerald-500/30 hover:shadow-xl transition flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-emerald-100 text-emerald-700">
                                {{ $item->komunitas->nama_komunitas ?? 'Umum' }}
                            </span>
                            <div class="flex items-center gap-1 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm font-medium">{{ $item->durasi }}</span>
                            </div>
                        </div>
                        <h3 class="text-2xl font-extrabold mb-3 text-gray-900">{{ $item->nama_paket }}</h3>
                        <p class="mb-6 text-sm leading-relaxed text-gray-600 line-clamp-2">{{ $item->deskripsi ?? 'Tidak ada deskripsi spesifik.' }}</p>
                    </div>
                    <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider mb-1 text-gray-500">Mulai</p>
                            <p class="text-xl font-black text-emerald-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('booking.create', $item->id) }}" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-center transition bg-gray-900 text-white hover:bg-emerald-500">Pesan</a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-gray-500">Belum ada paket wisata yang tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16">
        
        <div>
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900">Rute Destinasi</h2>
                <a href="{{ route('rute') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($rute as $item)
                <div class="p-4 rounded-2xl border border-gray-100 flex items-start gap-4 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
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
                            <div class="w-full h-full bg-emerald-100 flex items-center justify-center text-emerald-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
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
@endsection