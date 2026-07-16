@extends('frontend.layouts.app')

@section('title', 'Daftar Paket Wisata - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))

@section('content')

{{-- Default cover image jika paket tidak punya gambar --}}
@php
    $defaultCover = asset('images/placeholder-landscape.svg');
@endphp

<section class="bg-gray-50 min-h-screen">

    {{-- HERO SECTION PAKET --}}
    <div class="bg-white border-b border-gray-100 py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-black tracking-widest uppercase rounded-full mb-4">Pilihan Wisata Terbaik</span>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-4">Semua Paket Wisata</h1>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">Jelajahi berbagai pilihan paket wisata seru yang ditawarkan oleh komunitas Jeep Dieng kami.</p>
            
            {{-- SEARCH BAR --}}
            <form action="{{ route('paket') }}" method="GET" class="mt-8 max-w-2xl mx-auto relative">
                <div class="relative flex items-center w-full h-14 rounded-2xl focus-within:shadow-lg focus-within:border-emerald-300 bg-white overflow-hidden border border-gray-200 transition-all shadow-sm">
                    <div class="grid place-items-center h-full w-14 text-gray-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>

                    <input class="peer h-full w-full outline-none text-gray-700 pr-2 bg-transparent font-medium"
                           type="text"
                           name="search"
                           value="{{ $searchTerm ?? '' }}"
                           placeholder="Cari nama paket atau rute wisata (misal: Sikunir, Kawah)..." /> 
                    
                    <button type="submit" class="h-full px-8 bg-emerald-500 hover:bg-emerald-600 text-white font-bold transition flex items-center gap-2">
                        Cari
                    </button>
                </div>
                @if(isset($searchTerm) && $searchTerm != '')
                    <div class="text-left mt-3">
                        <a href="{{ route('paket') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Tampilkan semua paket &times;</a>
                    </div>
                @endif
            </form>

            <div class="w-24 h-1.5 bg-emerald-500 mx-auto mt-10 rounded-full"></div>
        </div>
    </div>

    {{-- GRID PAKET --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

        <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
            @if(isset($searchTerm) && $searchTerm != '')
                <p class="text-gray-600 font-medium">Hasil pencarian untuk: <span class="font-black text-gray-900 text-lg">"{{ $searchTerm }}"</span></p>
            @else
                <p class="text-gray-600 font-medium">Menampilkan <span class="font-black text-gray-900">{{ $paket->count() }}</span> paket tersedia</p>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($paket as $index => $item)

                @php
                    $coverImg = $item->gambar
                        ? asset('storage/' . $item->gambar)
                        : $defaultCover;
                @endphp

                <div class="bg-white rounded-[2rem] overflow-hidden border border-gray-100 hover:border-emerald-300 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group flex flex-col">

                    {{-- GAMBAR COVER --}}
                    <a href="{{ route('paket.show', $item->id) }}" class="block relative overflow-hidden" style="aspect-ratio: 16/9;">
                        <img
                            src="{{ $coverImg }}"
                            alt="{{ $item->nama_paket }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            loading="lazy"
                        >
                        {{-- Overlay gelap tipis --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-transparent"></div>

                        {{-- Badge komunitas di atas gambar --}}
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-emerald-700 text-xs font-black tracking-wider uppercase rounded-full shadow-sm">
                                {{ $item->komunitas->nama_komunitas ?? 'Umum' }}
                            </span>
                        </div>

                        {{-- Durasi di sudut kanan atas --}}
                        <div class="absolute top-3 right-3">
                            <span class="flex items-center gap-1 px-2.5 py-1 bg-gray-900/70 backdrop-blur-sm text-white text-xs font-bold rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $item->durasi }}
                            </span>
                        </div>

                        {{-- Icon zoom saat hover --}}
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            <span class="w-12 h-12 bg-white/20 backdrop-blur-sm border border-white/40 rounded-full flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </span>
                        </div>
                    </a>

                    {{-- KONTEN KARTU --}}
                    <div class="p-6 flex flex-col flex-grow">
                        @if($item->komunitas && $item->komunitas->review_count > 0)
                        <div class="flex items-center gap-1.5 mb-2">
                            <div class="flex items-center text-amber-400">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span class="text-sm font-bold text-gray-700 ml-1">{{ number_format($item->komunitas->average_rating, 1) }}</span>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">({{ $item->komunitas->review_count }} ulasan)</span>
                        </div>
                        @else
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="text-xs text-gray-400 font-medium italic">Belum ada ulasan</span>
                        </div>
                        @endif
                        <h3 class="text-xl font-extrabold text-gray-900 group-hover:text-emerald-600 transition mb-2 leading-tight">
                            <a href="{{ route('paket.show', $item->id) }}">{{ $item->nama_paket }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 flex-grow mb-3">
                            {{ $item->deskripsi ?? 'Nikmati petualangan seru di alam Dieng bersama supir profesional kami.' }}
                        </p>

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

                        {{-- HARGA & TOMBOL --}}
                        <div class="pt-5 border-t border-gray-100">
                            <div class="flex items-end justify-between mb-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-0.5">Harga Per Armada</p>
                                    <p class="text-2xl font-black text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                                {{-- Indikator tidak punya gambar --}}
                                @if(!$item->gambar)
                                    <span class="text-[10px] font-bold text-amber-500 bg-amber-50 border border-amber-200 px-2 py-1 rounded-lg uppercase tracking-wide">Belum ada foto</span>
                                @endif
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('paket.show', $item->id) }}"
                                   class="flex-1 py-3 rounded-xl font-bold text-center text-sm transition bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-100 hover:border-emerald-300">
                                    Lihat Detail
                                </a>
                                <a href="{{ route('booking.create', $item->id) }}"
                                   class="flex-1 py-3 rounded-xl font-bold text-center text-sm transition bg-gray-900 text-white hover:bg-emerald-500 shadow-lg hover:shadow-emerald-500/30 transform hover:-translate-y-0.5">
                                    Pesan Kini
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            @empty
                <div class="col-span-full text-center py-20">
                    <svg class="mx-auto h-24 w-24 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @if(isset($searchTerm) && $searchTerm != '')
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Oops! Paket tidak ditemukan</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">Kami tidak dapat menemukan paket wisata atau destinasi yang cocok dengan kata kunci <span class="font-bold text-gray-800">"{{ $searchTerm }}"</span>.</p>
                        <a href="{{ route('paket') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-100 text-emerald-700 font-bold rounded-xl hover:bg-emerald-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Paket Wisata</h3>
                        <p class="text-gray-500">Saat ini belum ada paket wisata yang ditambahkan.</p>
                        <a href="{{ route('home') }}" class="inline-block mt-6 px-6 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition">Kembali ke Beranda</a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection