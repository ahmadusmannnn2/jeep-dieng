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
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Koleksi Foto Galeri Pengunjung</label>
                <input type="file" name="gallery_images[]" multiple accept="image/*" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition cursor-pointer bg-gray-50">
                <p class="text-xs text-amber-600 mt-2 font-medium">*Pilih beberapa gambar terbaik Anda sekaligus. Gambar-gambar ini akan disusun secara estetik ala Pinterest di halaman beranda.</p>
                
                @if($pengaturan->gallery_images)
                    <div class="flex gap-4 mt-4 overflow-x-auto pb-2">
                        @foreach($pengaturan->gallery_images as $img)
                            <img src="{{ asset('storage/' . $img) }}" class="w-24 h-24 object-cover rounded-xl border border-gray-200 shadow-sm shrink-0">
                        @endforeach
                    </div>
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