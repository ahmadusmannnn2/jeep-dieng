@extends('admin.layouts.app')

@section('title', 'Pesanan Masuk - Jeep Dieng')
@section('header_title', 'Data Pesanan Masuk')
@section('header_subtitle', 'Pantau dan kelola seluruh reservasi dari wisatawan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Kode Booking</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Paket & Jadwal</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pesanan as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-gray-800">#BKG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $item->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->user->no_hp ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $item->paketWisata->nama_paket ?? 'Paket Terhapus' }}</p>
                        <p class="text-xs text-emerald-600 font-bold">
                            {{ $item->jadwal ? \Carbon\Carbon::parse($item->jadwal->tanggal)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($item->jadwal->jam)->format('H:i') : 'Jadwal Terhapus' }}
                        </p>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->status === 'Pending')
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">Menunggu</span>
                        @elseif($item->status === 'Disetujui')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Disetujui (Blm Lunas)</span>
                        @elseif($item->status === 'Lunas')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Lunas</span>
                        @elseif($item->status === 'Selesai')
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">Tour Selesai</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex justify-center">
                        <a href="{{ route('admin.pesanan.show', $item->id) }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-emerald-500 transition shadow-md">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection