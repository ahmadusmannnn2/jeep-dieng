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
            <img src="{{ asset('images/placeholder-promo.svg') }}" alt="Placeholder" class="w-full h-48 object-cover">
        @endif
        
        <div class="p-6 flex-1 flex flex-col">
            <span class="text-xs font-bold text-emerald-500 mb-2">{{ \Carbon\Carbon::parse($item->tanggal_publish)->translatedFormat('d F Y') }}</span>
            <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $item->judul }}</h4>
            <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-1">{{ $item->isi }}</p>
            
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.konten-informasi.edit', $item->id) }}" title="Edit Data" class="p-2.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </a>
                <form action="{{ route('admin.konten-informasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Hapus Data" class="p-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
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
