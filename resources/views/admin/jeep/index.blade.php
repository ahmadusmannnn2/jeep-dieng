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

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Nama Jeep</th>
                    <th class="px-6 py-4">Nomor Polisi</th>
                    <th class="px-6 py-4 text-center">Kapasitas</th>
                    <th class="px-6 py-4">Status</th>
                    @if(Auth::user()->role === 'super_admin')
                        <th class="px-6 py-4">Komunitas</th>
                    @endif
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
                    @if(Auth::user()->role === 'super_admin')
                        <td class="px-6 py-4 text-gray-600">{{ $jeep->komunitas->nama_komunitas ?? '-' }}</td>
                    @endif
                    <td class="px-6 py-4 flex justify-center gap-3">
                        <a href="{{ route('admin.jeep.edit', $jeep->id) }}" class="text-amber-500 hover:text-amber-600 font-medium">Edit</a>
                        <form action="{{ route('admin.jeep.destroy', $jeep->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus armada Jeep ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 font-medium">Hapus</button>
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