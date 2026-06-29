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
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4">Semua Paket Wisata</h1>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">Jelajahi berbagai pilihan paket wisata seru yang ditawarkan oleh komunitas Jeep Dieng kami.</p>
            <div class="w-24 h-1.5 bg-emerald-500 mx-auto mt-6 rounded-full"></div>
        </div>
    </div>

    {{-- GRID PAKET --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

        @if($paket->count() > 0)
            <p class="text-sm text-gray-400 font-medium mb-8">Menampilkan <span class="font-black text-gray-700">{{ $paket->count() }}</span> paket wisata tersedia</p>
        @endif

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
                        <h3 class="text-xl font-extrabold text-gray-900 group-hover:text-emerald-600 transition mb-2 leading-tight">
                            <a href="{{ route('paket.show', $item->id) }}">{{ $item->nama_paket }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 flex-grow mb-5">
                            {{ $item->deskripsi ?? 'Nikmati petualangan seru di alam Dieng bersama supir profesional kami.' }}
                        </p>

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
                <div class="col-span-full py-20 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900 mb-2">Belum Ada Paket Wisata</h3>
                    <p class="text-gray-500 text-sm">Paket wisata belum tersedia. Silakan cek kembali nanti.</p>
                    <a href="{{ route('home') }}" class="inline-block mt-6 px-6 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition">Kembali ke Beranda</a>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection