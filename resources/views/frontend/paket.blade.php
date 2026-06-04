@extends('frontend.layouts.app')

@section('title', 'Daftar Paket Wisata - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))

@section('content')
<section class="py-12 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Semua Paket Wisata</h2>
            <p class="mt-4 text-gray-500 max-w-2xl mx-auto">Jelajahi berbagai pilihan paket wisata yang ditawarkan oleh komunitas Jeep kami.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($paket as $index => $item)
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:border-emerald-500/30 hover:shadow-xl transition-all flex flex-col justify-between group">
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
                        <h3 class="text-2xl font-extrabold mb-3 text-gray-900 group-hover:text-emerald-600 transition">{{ $item->nama_paket }}</h3>
                        
                        <p class="mb-6 text-sm leading-relaxed text-gray-600 line-clamp-3">{{ $item->deskripsi ?? 'Tidak ada deskripsi spesifik.' }}</p>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-200 flex flex-col gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider mb-1 text-gray-500">Harga Mulai</p>
                            <p class="text-xl font-black text-emerald-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                        
                        <div class="flex gap-2 w-full mt-2">
                            <a href="{{ route('paket.show', $item->id) }}" class="flex-1 px-4 py-3 rounded-xl font-bold text-center transition bg-white text-emerald-700 hover:bg-emerald-50 border border-emerald-200">
                                Detail
                            </a>
                            <a href="{{ route('booking.create', $item->id) }}" class="flex-1 px-4 py-3 rounded-xl font-bold text-center transition bg-gray-900 text-white hover:bg-emerald-500 shadow-md hover:shadow-emerald-500/30">
                                Pesan
                            </a>
                        </div>
                    </div>
                    
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">Belum ada paket wisata.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection