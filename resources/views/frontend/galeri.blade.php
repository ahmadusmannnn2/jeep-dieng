@extends('frontend.layouts.app')

@section('title', 'Galeri Petualangan - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))

@section('content')

@php
    // Fallback jika admin belum mengunggah foto galeri
    $defaultGallery = [
        ['type' => 'image', 'src' => asset('images/placeholder-landscape.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-square.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-promo.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-landscape.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-square.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-promo.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-landscape.svg')],
        ['type' => 'image', 'src' => asset('images/placeholder-square.svg')],
    ];

    // Gabungkan foto dan video menjadi satu array media
    $galleryMedia = [];
    if (isset($pengaturan_website) && (!empty($pengaturan_website->gallery_images) || !empty($pengaturan_website->gallery_videos))) {
        if (!empty($pengaturan_website->gallery_images)) {
            foreach ($pengaturan_website->gallery_images as $img) {
                $galleryMedia[] = ['type' => 'image', 'src' => asset('storage/' . $img)];
            }
        }
        if (!empty($pengaturan_website->gallery_videos)) {
            foreach ($pengaturan_website->gallery_videos as $vid) {
                $galleryMedia[] = ['type' => 'video', 'src' => asset('storage/' . $vid)];
            }
        }
    } else {
        $galleryMedia = $defaultGallery;
    }

    $totalFoto  = count(array_filter($galleryMedia, fn($m) => $m['type'] === 'image'));
    $totalVideo = count(array_filter($galleryMedia, fn($m) => $m['type'] === 'video'));
@endphp

<section
    x-data="{
        lightboxOpen: false,
        activeMedia: { type: '', src: '' },
        openLightbox(type, src) {
            this.activeMedia = { type, src };
            this.lightboxOpen = true;
        },
        closeLightbox() {
            this.lightboxOpen = false;
            this.$nextTick(() => {
                const vid = this.$refs.lightboxVideo;
                if (vid) vid.pause();
            });
        }
    }"
    x-effect="document.body.style.overflow = lightboxOpen ? 'hidden' : ''"
    class="py-20 bg-gray-50 min-h-screen"
>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-black tracking-widest uppercase rounded-full mb-4">Koleksi Kami</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4">Galeri Petualangan</h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto">Kumpulan foto dan video momen epik penjelajahan alam Dieng bersama armada Jeep kami.</p>
            <div class="w-24 h-1.5 bg-emerald-500 mx-auto mt-6 rounded-full"></div>

            {{-- STATISTIK MEDIA --}}
            <div class="flex items-center justify-center gap-6 mt-8">
                <div class="flex items-center gap-2 text-sm font-bold text-gray-600">
                    <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </span>
                    {{ $totalFoto }} Foto
                </div>
                @if($totalVideo > 0)
                <div class="flex items-center gap-2 text-sm font-bold text-gray-600">
                    <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </span>
                    {{ $totalVideo }} Video
                </div>
                @endif
            </div>
        </div>

        {{-- GRID MASONRY --}}
        <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-5 space-y-5">
            @forelse($galleryMedia as $index => $media)
                @if($media['type'] === 'image')
                    {{-- FOTO --}}
                    <div
                        @click="openLightbox('image', '{{ $media['src'] }}')"
                        class="relative group rounded-2xl overflow-hidden break-inside-avoid shadow-sm hover:shadow-2xl transition duration-500 cursor-zoom-in"
                    >
                        <img
                            src="{{ $media['src'] }}"
                            alt="Galeri Petualangan {{ $index + 1 }}"
                            class="w-full h-auto object-cover group-hover:scale-110 transition duration-700"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white transform scale-50 group-hover:scale-100 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- VIDEO --}}
                    <div
                        @click="openLightbox('video', '{{ $media['src'] }}')"
                        class="relative group rounded-2xl overflow-hidden break-inside-avoid shadow-sm hover:shadow-2xl transition duration-500 cursor-pointer"
                    >
                        <video
                            src="{{ $media['src'] }}"
                            class="w-full h-auto object-cover"
                            muted
                            preload="metadata"
                        ></video>
                        {{-- Overlay play button untuk video --}}
                        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition duration-300 flex items-center justify-center">
                            <div class="w-14 h-14 bg-white/25 backdrop-blur-md border-2 border-white/50 rounded-full flex items-center justify-center text-white group-hover:scale-110 transition duration-300">
                                <svg class="w-7 h-7 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                        {{-- Badge VIDEO --}}
                        <span class="absolute top-3 right-3 bg-purple-600/90 backdrop-blur-sm text-white text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            VIDEO
                        </span>
                    </div>
                @endif
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-gray-400 font-medium">Belum ada media galeri.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- LIGHTBOX MODAL --}}
    <div
        x-show="lightboxOpen"
        style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/96 backdrop-blur-md p-4 md:p-10"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="closeLightbox()"
    >
        {{-- Tombol Tutup --}}
        <button
            @click="closeLightbox()"
            class="absolute top-5 right-5 md:top-8 md:right-8 z-10 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2.5 transition backdrop-blur-sm border border-white/20"
        >
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        {{-- Kontainer Media --}}
        <div
            @click.self="closeLightbox()"
            class="flex items-center justify-center w-full h-full"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
        >
            {{-- Tampilkan GAMBAR jika tipe image --}}
            <template x-if="activeMedia.type === 'image'">
                <img
                    :src="activeMedia.src"
                    @click.stop
                    class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl"
                    alt="Galeri Lightbox"
                >
            </template>

            {{-- Tampilkan VIDEO jika tipe video --}}
            <template x-if="activeMedia.type === 'video'">
                <video
                    x-ref="lightboxVideo"
                    :src="activeMedia.src"
                    @click.stop
                    class="max-w-full max-h-[90vh] rounded-xl shadow-2xl"
                    controls
                    autoplay
                    style="max-width: min(90vw, 960px);"
                ></video>
            </template>
        </div>
    </div>

</section>

@endsection