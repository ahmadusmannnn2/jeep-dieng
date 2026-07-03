@extends('admin.layouts.app')

@section('title', 'Daftar Rute Wisata - Jeep Dieng')
@section('header_title', 'Manajemen Rute Wisata')
@section('header_subtitle', 'Kelola destinasi dan titik kumpul perjalanan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-black text-gray-900">Daftar Destinasi / Rute</h3>
    <a href="{{ route('admin.rute-wisata.create') }}" class="px-5 py-2.5 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Rute Baru
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6 p-6">
    <form action="{{ route('admin.rute-wisata.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama rute..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition shadow-lg">Filter</button>
            <a href="{{ route('admin.rute-wisata.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                    <th class="p-4 font-bold w-24 text-center">Foto</th>
                    <th class="p-4 font-bold">Nama Destinasi / Rute</th>

                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($ruteWisata as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-center">
                        <div class="w-20 h-14 rounded-lg overflow-hidden border border-gray-200 mx-auto bg-gray-100">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="font-bold text-gray-900 block text-base">{{ $item->nama_rute }}</span>
                        <span class="text-xs text-gray-500 truncate max-w-md block mt-1">{{ Str::limit($item->deskripsi, 80) }}</span>
                    </td>

                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.rute-wisata.edit', $item->id) }}" class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.rute-wisata.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus destinasi ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="p-8 text-center text-gray-500">Belum ada rute destinasi wisata.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
