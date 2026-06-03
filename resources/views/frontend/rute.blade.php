@extends('frontend.layouts.app')
@section('title', 'Rute Wisata - Jeep Dieng')
@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Rute Trip Favorit</h2>
            <p class="mt-4 text-gray-500">Destinasi yang siap memanjakan mata Anda.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($rute as $item)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4 hover:border-emerald-300 transition">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $item->nama_rute }}</h4>
                    <p class="text-sm text-gray-500">{{ $item->deskripsi ?: 'Jelajahi keindahan rute ini bersama armada tangguh kami.' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection