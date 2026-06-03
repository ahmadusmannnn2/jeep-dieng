@extends('frontend.layouts.app')
@section('title', 'Info & Promo - Jeep Dieng')
@section('content')
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kabar & Promo Terbaru</h2>
            <p class="mt-4 text-gray-500">Temukan penawaran terbaik dan info penting seputar Dieng.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($promo as $item)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl transition">
                <div class="relative h-56 overflow-hidden">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="Promo" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                            <span class="text-white font-bold opacity-50">JEEP DIENG</span>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-emerald-600 shadow-sm">
                        {{ \Carbon\Carbon::parse($item->tanggal_publish)->translatedFormat('d M Y') }}
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $item->judul }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $item->isi }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-8">Belum ada info atau promo saat ini.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection