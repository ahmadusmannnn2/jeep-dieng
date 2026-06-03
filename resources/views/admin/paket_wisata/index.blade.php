@extends('admin.layouts.app')

@section('title', 'Paket Wisata - Jeep Dieng')
@section('header_title', 'Data Paket Wisata')
@section('header_subtitle', 'Kelola daftar paket perjalanan yang ditawarkan ke wisatawan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-bold text-gray-800">Daftar Paket Wisata</h3>
    <a href="{{ route('admin.paket-wisata.create') }}" class="px-5 py-2.5 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Paket
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Nama Paket</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Durasi</th>
                    @if(Auth::user()->role === 'super_admin')
                        <th class="px-6 py-4">Komunitas</th>
                    @endif
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($paketWisata as $paket)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $paket->nama_paket }}</td>
                    <td class="px-6 py-4 text-emerald-600 font-bold">Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $paket->durasi }}</td>
                    @if(Auth::user()->role === 'super_admin')
                        <td class="px-6 py-4 text-gray-600">{{ $paket->komunitas->nama_komunitas ?? '-' }}</td>
                    @endif
                    <td class="px-6 py-4 flex justify-center gap-3">
                        <a href="{{ route('admin.paket-wisata.edit', $paket->id) }}" class="text-amber-500 hover:text-amber-600 font-medium">Edit</a>
                        <form action="{{ route('admin.paket-wisata.destroy', $paket->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus paket wisata ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data paket wisata.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection