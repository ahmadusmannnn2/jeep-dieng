@extends('frontend.layouts.app')

@section('title', 'Rute Perjalanan - Jeep Dieng')

@section('content')

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<div class="bg-gray-50 py-20 min-h-screen font-sans overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-down" data-aos-duration="1000">
            <span class="text-emerald-600 font-bold tracking-wider uppercase text-xs bg-emerald-50 px-4 py-1.5 rounded-full border border-emerald-100 shadow-sm">
                Perjalanan Kami
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-5 mb-4 tracking-tight">Eksplorasi Jalur Dieng</h1>
            <p class="text-gray-500 md:text-lg leading-relaxed px-4">
                Dari titik kumpul hingga puncak tertinggi. Ikuti alur perjalanan Jeep kami menyusuri keajaiban alam Dataran Tinggi Dieng yang memukau.
            </p>
        </div>

        <div class="relative container mx-auto px-4 sm:px-0">
            
            <div class="hidden md:block absolute z-0 w-1 h-full left-1/2 transform -translate-x-1/2 rounded-full bg-gradient-to-b from-emerald-100 via-emerald-300 to-teal-500 opacity-50"></div>
            
            <div class="block md:hidden absolute z-0 w-1 h-full left-8 rounded-full bg-gradient-to-b from-emerald-100 via-emerald-300 to-teal-500 opacity-50"></div>

            <div class="space-y-12 md:space-y-16">
                @forelse($ruteWisata as $index => $rute)
                    @php
                        // Logika Zig-Zag: Genap di Kiri, Ganjil di Kanan
                        $isEven = $index % 2 == 0;
                        $gambarRute = $rute->gambar ? asset('storage/' . $rute->gambar) : asset('images/placeholder-landscape.svg');
                    @endphp

                    <div class="relative z-10 flex flex-col md:flex-row items-center w-full group">
                        
                        <div class="absolute left-8 md:left-1/2 transform -translate-x-1/2 flex flex-col items-center justify-center z-20" data-aos="zoom-in" data-aos-delay="200">
                            <div class="hidden md:block absolute top-1/2 w-12 h-px bg-emerald-300 border-dashed {{ $isEven ? 'right-full' : 'left-full' }} -z-10"></div>
                            
                            <div class="w-10 h-10 md:w-14 md:h-14 rounded-full bg-white border-[3px] border-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.3)] flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-500 transition-all duration-500">
                                <span class="text-emerald-500 group-hover:text-white text-lg font-bold transition-colors">{{ $index + 1 }}</span>
                            </div>
                        </div>

                        <div class="w-full md:w-1/2 pl-20 md:pl-0 {{ $isEven ? 'md:pr-16 text-left md:text-right' : 'md:pl-16 md:ml-auto text-left' }}" 
                             data-aos="{{ $isEven ? 'fade-right' : 'fade-left' }}" 
                             data-aos-duration="1000">
                            
                            <div class="bg-white rounded-3xl p-4 shadow-md border border-gray-100 hover:shadow-xl hover:border-emerald-200 transition-all duration-500 transform hover:-translate-y-1 relative overflow-hidden group/card">
                                
                                <div class="absolute top-5 {{ $isEven ? 'right-5 md:left-5 md:right-auto' : 'right-5' }} z-20">
                                    <span class="md:hidden px-3 py-1 bg-emerald-500 text-white text-[10px] font-bold rounded-lg shadow-sm uppercase tracking-wider">Ke-{{ $index + 1 }}</span>
                                </div>

                                <div class="w-full h-48 md:h-56 rounded-2xl overflow-hidden mb-5 relative">
                                    <img src="{{ $gambarRute }}" alt="{{ $rute->nama_rute }}" class="w-full h-full object-cover transform group-hover/card:scale-105 transition duration-700 ease-in-out">
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-gray-900/10 to-transparent"></div>
                                    
                                    <div class="absolute bottom-4 {{ $isEven ? 'md:right-4 left-4 md:left-auto' : 'left-4' }}">
                                        @if($rute->komunitas)
                                            <span class="px-2.5 py-1 bg-emerald-500/90 backdrop-blur-sm text-white text-[10px] font-bold rounded-md uppercase tracking-wider shadow-sm">
                                                {{ $rute->komunitas?->nama_komunitas }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="px-2 md:px-3 pb-2">
                                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">{{ $rute->nama_rute }}</h3>
                                    <p class="text-gray-500 text-sm leading-relaxed">
                                        {{ $rute->deskripsi ?? 'Abadikan momen terbaik Anda bersama keluarga dan kerabat di destinasi menakjubkan ini. Rasakan sejuknya udara pegunungan Dieng.' }}
                                    </p>
                                </div>
                                
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="w-full flex justify-center pt-10" data-aos="fade-up">
                        <div class="bg-white p-10 rounded-3xl shadow-lg border border-gray-100 text-center max-w-md">
                            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Rute Belum Tersedia</h3>
                            <p class="text-gray-500 text-sm">Admin kami sedang menyusun titik-titik perjalanan yang menakjubkan untuk Anda.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
        </div>
        
        @if(isset($ruteWisata) && count($ruteWisata) > 0)
        <div class="mt-32 text-center" data-aos="zoom-in" data-aos-duration="1000">
            <h3 class="text-3xl font-extrabold text-gray-900 mb-4">Mulai Petualangan Anda Sekarang!</h3>
            <p class="text-gray-500 mb-8 max-w-2xl mx-auto text-lg">Pilih paket perjalanan yang mencakup rute-rute impian Anda di atas, dan pesan Jeep Anda hari ini.</p>
            <a href="{{ route('paket') }}" class="inline-flex items-center justify-center px-10 py-5 bg-emerald-500 text-white font-black text-xl rounded-2xl hover:bg-emerald-400 transition shadow-[0_10px_25px_rgba(16,185,129,0.4)] transform hover:-translate-y-2 gap-3 relative overflow-hidden group">
                <span class="absolute inset-0 w-full h-full bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition duration-700"></span>
                Pilih Paket Wisata
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l7-7m7-7H3"></path></svg>
            </a>
        </div>
        @endif

    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            once: true, // Animasi hanya berjalan satu kali saat di-scroll ke bawah
            offset: 100, // Jarak trigger animasi dari bawah layar
        });
    });
</script>
@endsection