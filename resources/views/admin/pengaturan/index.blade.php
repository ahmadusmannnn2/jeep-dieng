@extends('admin.layouts.app')

@section('title', 'Pengaturan Umum - Jeep Dieng')
@section('header_title', 'Pengaturan Website')
@section('header_subtitle', 'Kelola identitas merek, logo, dan konten publik')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-5xl mx-auto">
    <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
        @csrf
        @method('PUT')

        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-6">1. Identitas Merek (Branding)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Website</label>
                    <input type="text" name="nama_website" value="{{ $pengaturan->nama_website }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Logo Website</label>
                    <div class="flex items-center gap-4 mb-3">
                        @if($pengaturan->logo)
                            <div class="w-16 h-16 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-center overflow-hidden p-2 shrink-0">
                                <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-6">2. Konten Halaman Depan (Hero Section)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Teks Lencana (Badge)</label>
                    <input type="text" name="hero_badge" value="{{ $pengaturan->hero_badge }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Utama (Teks Putih)</label>
                    <input type="text" name="hero_title" value="{{ $pengaturan->hero_title }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-emerald-600 mb-2">Judul Sorotan (Teks Hijau Besar)</label>
                    <input type="text" name="hero_title_highlight" value="{{ $pengaturan->hero_title_highlight }}" class="w-full px-4 py-3 rounded-xl border border-emerald-200 focus:ring-2 focus:ring-emerald-500 transition bg-emerald-50">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Sub-judul</label>
                    <textarea name="hero_subtitle" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">{{ $pengaturan->hero_subtitle }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Gambar Background Bergerak (Slider Hero)</label>
                    <input type="file" name="hero_images[]" multiple accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition cursor-pointer bg-gray-50">
                    <p class="text-xs text-amber-600 mt-2 font-medium">*Unggah beberapa gambar sekaligus untuk mengganti slider lama.</p>
                    
                    @if($pengaturan->hero_images)
                        <div class="flex gap-4 mt-4 overflow-x-auto pb-2">
                            @foreach($pengaturan->hero_images as $img)
                                <img src="{{ asset('storage/' . $img) }}" class="w-32 h-20 object-cover rounded-xl border border-gray-200 shadow-sm shrink-0">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-6">3. Galeri Petualangan</h3>
            
            {{-- UPLOAD FOTO GALERI --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-1">📸 Koleksi Foto Galeri</label>
                <p class="text-xs text-gray-500 mb-3">Format: JPG, PNG, WEBP. Maks 3MB per foto. Unggah ulang untuk mengganti semua foto lama.</p>
                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer bg-gray-50 hover:bg-emerald-50 hover:border-emerald-400 transition-all group">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-sm text-gray-500 group-hover:text-emerald-600"><span class="font-bold">Klik untuk pilih foto</span> atau drag & drop</p>
                        <p class="text-xs text-gray-400 mt-1">Pilih banyak sekaligus</p>
                    </div>
                    <input type="file" name="gallery_images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                </label>

                @if($pengaturan->gallery_images && count($pengaturan->gallery_images) > 0)
                    <div class="mt-4">
                        <p class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">
                            Foto tersimpan saat ini: <span class="text-emerald-600">{{ count($pengaturan->gallery_images) }} foto</span>
                        </p>
                        <div class="flex gap-3 overflow-x-auto pb-2">
                            @foreach($pengaturan->gallery_images as $img)
                                <div class="relative shrink-0 group">
                                    <img src="{{ asset('storage/' . $img) }}" class="w-24 h-24 object-cover rounded-xl border-2 border-gray-200 shadow-sm group-hover:border-emerald-400 transition">
                                    <span class="absolute top-1 right-1 bg-emerald-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full uppercase">IMG</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-xs text-gray-400 mt-3 italic">Belum ada foto galeri yang diunggah.</p>
                @endif
            </div>

            {{-- UPLOAD VIDEO GALERI --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">🎬 Koleksi Video Galeri</label>
                <p class="text-xs text-gray-500 mb-3">Format: MP4, WEBM, MOV, AVI. Maks 50MB per video. Unggah ulang untuk mengganti semua video lama.</p>
                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-purple-300 rounded-2xl cursor-pointer bg-purple-50/50 hover:bg-purple-50 hover:border-purple-400 transition-all group">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-2 text-purple-400 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <p class="text-sm text-gray-500 group-hover:text-purple-600"><span class="font-bold">Klik untuk pilih video</span> atau drag & drop</p>
                        <p class="text-xs text-gray-400 mt-1">MP4, WEBM, MOV, AVI</p>
                    </div>
                    <input type="file" name="gallery_videos[]" multiple accept="video/mp4,video/webm,video/quicktime,video/avi" class="hidden">
                </label>

                @if($pengaturan->gallery_videos && count($pengaturan->gallery_videos) > 0)
                    <div class="mt-4">
                        <p class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">
                            Video tersimpan saat ini: <span class="text-purple-600">{{ count($pengaturan->gallery_videos) }} video</span>
                        </p>
                        <div class="flex gap-3 overflow-x-auto pb-2">
                            @foreach($pengaturan->gallery_videos as $video)
                                <div class="relative shrink-0 group w-40">
                                    <video src="{{ asset('storage/' . $video) }}" class="w-40 h-24 object-cover rounded-xl border-2 border-gray-200 shadow-sm group-hover:border-purple-400 transition" muted></video>
                                    <span class="absolute top-1 right-1 bg-purple-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full uppercase">VIDEO</span>
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                        <div class="w-8 h-8 bg-black/50 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-xs text-gray-400 mt-3 italic">Belum ada video galeri yang diunggah.</p>
                @endif
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-6">4. Informasi Kontak & Footer</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon (CS)</label>
                    <input type="text" name="no_telp" value="{{ $pengaturan->no_telp }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Publik</label>
                    <input type="email" name="email" value="{{ $pengaturan->email }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Kantor/Basecamp</label>
                <input type="text" name="alamat" value="{{ $pengaturan->alamat }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat (Footer)</label>
                <textarea name="deskripsi_footer" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">{{ $pengaturan->deskripsi_footer }}</textarea>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-10 py-4 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Simpan Pembaruan</button>
        </div>
    </form>
</div>
@endsection
