@extends('admin.layouts.app')

@section('title', 'Data Testimoni - Jeep Dieng')
@section('header_title', 'Kelola Testimoni')
@section('header_subtitle', 'Tampilkan ulasan terbaik dari pelanggan di halaman depan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-bold text-gray-800">Daftar Testimoni</h3>
    <a href="{{ route('admin.testimoni.create') }}" class="px-5 py-2.5 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Testimoni
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6 p-6">
    <form action="{{ route('admin.testimoni.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kota, atau pesan testimoni..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition shadow-lg">Cari</button>
            <a href="{{ route('admin.testimoni.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Nama Pelanggan</th>
                    <th class="px-6 py-4">Pesan Ulasan</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($testimonis as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800 block">{{ $item->nama }}</span>
                        <span class="text-xs text-gray-500">{{ $item->asal_kota ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <div class="max-w-md whitespace-normal line-clamp-2">"{{ $item->pesan }}"</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.testimoni.toggle', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold transition {{ $item->is_tampil ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}" title="Klik untuk mengubah status">
                                {{ $item->is_tampil ? 'Ditampilkan' : 'Disembunyikan' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 flex justify-center gap-3">
                        <a href="{{ route('admin.testimoni.edit', $item->id) }}" class="text-amber-500 hover:text-amber-600 font-medium">Edit</a>
                        <form action="{{ route('admin.testimoni.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada data testimoni.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
