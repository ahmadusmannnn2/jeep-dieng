@extends('admin.layouts.app')

@section('title', 'Konten Informasi - Jeep Dieng')
@section('header_title', 'Info & Promo Wisata')
@section('header_subtitle', 'Kelola artikel dan promo yang tampil di halaman depan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-bold text-gray-800">Daftar Konten</h3>
    <a href="{{ route('admin.konten-informasi.create') }}" class="px-5 py-2.5 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tulis Artikel
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6 p-6">
    <form action="{{ route('admin.konten-informasi.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul konten..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition shadow-lg">Cari</button>
            <a href="{{ route('admin.konten-informasi.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($konten as $item)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition">
        @if($item->gambar)
            <img src="{{ asset('storage/' . $item->gambar) }}" alt="Thumbnail" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">Tidak ada gambar</div>
        @endif
        
        <div class="p-6 flex-1 flex flex-col">
            <span class="text-xs font-bold text-emerald-500 mb-2">{{ \Carbon\Carbon::parse($item->tanggal_publish)->translatedFormat('d F Y') }}</span>
            <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $item->judul }}</h4>
            <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-1">{{ $item->isi }}</p>
            
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.konten-informasi.edit', $item->id) }}" class="flex-1 py-2 text-center bg-amber-50 text-amber-600 font-medium rounded-xl hover:bg-amber-100 transition">Edit</a>
                <form action="{{ route('admin.konten-informasi.destroy', $item->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2 text-center bg-red-50 text-red-600 font-medium rounded-xl hover:bg-red-100 transition">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-3xl shadow-sm border border-gray-100">
        Belum ada konten artikel atau promo.
    </div>
    @endforelse
</div>
@endsection