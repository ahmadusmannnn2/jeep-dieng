@extends('admin.layouts.app')

@section('title', 'Data Komunitas')
@section('header_title', 'Data Komunitas Jeep')
@section('header_subtitle', 'Manajemen daftar mitra komunitas yang beroperasi di Dieng')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <form method="GET" action="{{ route('admin.komunitas.index') }}" class="w-full sm:w-1/3 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari komunitas atau ketua..." class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
            <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition text-sm font-medium whitespace-nowrap">
                Cari
            </button>
        </form>
        <a href="{{ route('admin.komunitas.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition font-medium text-sm flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Komunitas
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-100 text-sm text-gray-500">
                    <th class="py-4 px-4 font-semibold">Nama Komunitas</th>
                    <th class="py-4 px-4 font-semibold">Akun & Penanggung Jawab</th>
                    <th class="py-4 px-4 font-semibold">Kontak WA</th>
                    <th class="py-4 px-4 font-semibold">Total Armada</th>
                    <th class="py-4 px-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($komunitas as $k)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-4 px-4">
                        <div class="font-bold text-gray-800">{{ $k->nama_komunitas }}</div>
                        <div class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $k->alamat ?? 'Alamat belum diatur' }}</div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="text-sm font-bold text-gray-800">{{ $k->ketua ?? 'Belum ada nama' }}</div>
                        @php $pengelola = $k->users->where('role', 'pengelola')->first(); @endphp
                        @if($pengelola)
                            <div class="text-xs font-mono text-emerald-600 mt-0.5">{{ $pengelola->email }}</div>
                        @else
                            <div class="text-xs text-red-500 mt-0.5">Belum ada akun login</div>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-sm text-gray-700">
                        {{ $k->no_hp ?? '-' }}
                    </td>
                    <td class="py-4 px-4 text-sm">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 font-medium border border-emerald-100">
                            {{ $k->jeep_count }} Jeep
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-medium border border-blue-100 mt-1 sm:mt-0 sm:ml-1">
                            {{ $k->supir_count }} Supir
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.komunitas.edit', $k->id) }}" class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.komunitas.destroy', $k->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus komunitas ini? Seluruh akun pengelola yang terhubung juga akan dihapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">Belum ada data komunitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection