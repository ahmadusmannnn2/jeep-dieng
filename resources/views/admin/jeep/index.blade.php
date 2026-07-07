@extends('admin.layouts.app')

@section('title', 'Data Jeep - Jeep Dieng')
@section('header_title', 'Data Armada Jeep')
@section('header_subtitle', 'Kelola informasi kendaraan Jeep yang tersedia')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-bold text-gray-800">Daftar Armada Aktif</h3>
    <a href="{{ route('admin.jeep.create') }}" class="px-5 py-2.5 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Jeep
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6 p-6">
    <form action="{{ route('admin.jeep.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama jeep atau nomor polisi..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">Semua Status</option>
                <option value="Tersedia" {{ request('status') === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Disewa" {{ request('status') === 'Disewa' ? 'selected' : '' }}>Disewa</option>
                <option value="Perbaikan" {{ request('status') === 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
            </select>
        </div>
        <div class="w-full md:w-64">
            <select name="komunitas_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">Semua Komunitas</option>
                @foreach($komunitas as $kom)
                    <option value="{{ $kom->id }}" {{ request('komunitas_id') == $kom->id ? 'selected' : '' }}>{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition shadow-lg">Filter</button>
            <a href="{{ route('admin.jeep.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Nama Jeep</th>
                    <th class="px-6 py-4">Nomor Polisi</th>
                    <th class="px-6 py-4 text-center">Kapasitas</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Komunitas</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($jeeps as $jeep)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $jeep->nama_jeep }}</td>
                    <td class="px-6 py-4 font-bold text-gray-700">{{ $jeep->nomor_polisi }}</td>
                    <td class="px-6 py-4 text-center text-gray-600">{{ $jeep->kapasitas }} Orang</td>
                    <td class="px-6 py-4">
                        @if($jeep->status === 'Tersedia')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Tersedia</span>
                        @elseif($jeep->status === 'Disewa')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Disewa</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Perbaikan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                {{ $jeep->komunitas->nama_komunitas ?? '-' }}
                            </span>
                        </td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <a href="{{ route('admin.jeep.edit', $jeep->id) }}" title="Edit Data" class="p-2.5 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                        <form action="{{ route('admin.jeep.destroy', $jeep->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus armada Jeep ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus Data" class="p-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data Jeep.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
