@extends('frontend.layouts.app')

@section('title', 'Galeri Petualangan - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))

@section('content')

@php
    // Fallback jika admin belum mengunggah foto galeri
    $defaultGallery = [
        'https://images.unsplash.com/photo-1533692328991-08159ff19fca?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1542281286-9e0a16bb7366?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1535492984851-bc015f3e2ff5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1517581177682-a085bb7ffb15?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1520645521318-f06a70e20113?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1506012787146-f92b2d7d6d96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
    ];
    
    $galleryImages = (isset($pengaturan_website) && !empty($pengaturan_website->gallery_images)) 
        ? array_map(function($img) { return asset('storage/' . $img); }, $pengaturan_website->gallery_images)
        : $defaultGallery;
@endphp

<section x-data="{ lightboxOpen: false, activeImage: '' }" 
         x-effect="document.body.style.overflow = lightboxOpen ? 'hidden' : ''" 
         class="py-20 bg-gray-50 min-h-screen">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4">Galeri Petualangan</h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto">Koleksi momen epik dan keseruan penjelajahan alam Dieng bersama armada Jeep kami.</p>
            <div class="w-24 h-1.5 bg-emerald-500 mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6 space-y-6">
            @foreach($galleryImages as $index => $img)
                <div @click="activeImage = '{{ $img }}'; lightboxOpen = true" 
                     class="relative group rounded-2xl overflow-hidden break-inside-avoid shadow-sm hover:shadow-2xl transition duration-500 cursor-zoom-in">
                    
                    <img src="{{ $img }}" alt="Galeri Petualangan {{ $index + 1 }}" class="w-full h-auto object-cover group-hover:scale-110 transition duration-700">
                    
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white transform scale-50 group-hover:scale-100 transition duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    <div x-show="lightboxOpen" 
         style="display: none;" 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/95 backdrop-blur-sm p-4 md:p-10"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <button @click="lightboxOpen = false" class="absolute top-6 right-6 md:top-10 md:right-10 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 rounded-full p-2 transition">
            <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <img :src="activeImage" 
             @click.away="lightboxOpen = false" 
             class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100" />
             
    </div>

</section>

@endsection