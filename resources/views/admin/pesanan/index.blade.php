@extends('admin.layouts.app')

@section('title', 'Pesanan Masuk - Jeep Dieng')
@section('header_title', 'Data Pesanan Masuk')
@section('header_subtitle', 'Pantau dan kelola seluruh reservasi dari wisatawan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6 p-6">
    <form action="{{ route('admin.pesanan.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID pesanan, nama, atau no HP..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Menunggu Pembayaran (Pending)</option>
                <option value="DP Lunas" {{ request('status') === 'DP Lunas' ? 'selected' : '' }}>DP Lunas</option>
                <option value="Lunas" {{ request('status') === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
        @if(Auth::user()->role !== 'pengelola')
        <div class="w-full md:w-64">
            <select name="komunitas_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">Semua Komunitas</option>
                @foreach($komunitas as $kom)
                    <option value="{{ $kom->id }}" {{ request('komunitas_id') == $kom->id ? 'selected' : '' }}>{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition shadow-lg">Filter</button>
            <a href="{{ route('admin.pesanan.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Kode Booking</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Paket & Jadwal</th>
                    <th class="px-6 py-4">Armada Ditugaskan</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pesanan as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-gray-800">#BKG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $item->user->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-500">{{ $item->user->no_hp ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $item->paketWisata->nama_paket ?? 'Paket Terhapus' }}</p>
                        <p class="text-xs text-emerald-600 font-bold">
                            {{ \Carbon\Carbon::parse($item->tanggal_jadwal)->format('d/m/Y') }}
                        </p>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->armadas->count() > 0)
                            <div class="space-y-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px] border border-emerald-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    {{ $item->armadas->count() }} Jeep ({{ $item->komunitas->nama_komunitas ?? '-' }})
                                </span>
                                <div class="text-[11px] text-gray-600 bg-gray-50 p-2 rounded-xl border border-gray-100 space-y-1">
                                    @foreach($item->armadas as $armada)
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-0.5 sm:gap-1">
                                            <span class="font-bold text-gray-800">🚘 {{ $armada->jeep->nama_jeep ?? 'Jeep' }}</span>
                                            <span class="text-gray-500">({{ $armada->jeep->nomor_polisi ?? '-' }})</span>
                                            <span class="hidden sm:inline text-gray-300">|</span>
                                            <span class="text-gray-700">👤 {{ $armada->supir->nama_supir ?? 'Belum ada supir' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-700 font-bold text-xs border border-red-200">
                                Belum ada Jeep
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($item->status === 'Pending')
                            <span class="px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-bold border border-amber-200">Menunggu Pembayaran</span>
                        @elseif($item->status === 'DP Lunas')
                            <span class="px-3 py-1 bg-teal-50 text-teal-700 rounded-full text-xs font-bold border border-teal-200">DP Lunas (50%)</span>
                        @elseif($item->status === 'Selesai Perjalanan')
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold border border-blue-200">Selesai Perjalanan</span>
                        @elseif($item->status === 'Lunas')
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-200">Lunas</span>
                        @elseif($item->status === 'Selesai')
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-200">Selesai</span>
                        @else
                            <span class="px-3 py-1 bg-red-50 text-red-700 rounded-full text-xs font-bold border border-red-200">Batal</span>
                        @endif

                        @if($item->pembayaran && $item->pembayaran->status === 'Menunggu Verifikasi')
                            <span class="block mt-1 text-[10px] text-emerald-600 font-bold animate-pulse">Menunggu Validasi!</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <a href="{{ route('admin.pesanan.show', $item->id) }}" title="Lihat Detail" class="p-2.5 bg-gray-100 text-gray-700 hover:bg-gray-900 hover:text-white rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                        @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'pengelola' && $item->armadas->count() > 0))
                        <a href="{{ route('booking.print', $item->id) }}" target="_blank" title="Cetak Surat Jalan" class="p-2.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </a>
                        @endif
                        @if(Auth::user()->role === 'admin')
                        @if(!in_array($item->status, ['DP Lunas', 'Selesai Perjalanan', 'Lunas', 'Selesai']))
                        <form action="{{ route('admin.pesanan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus Pesanan" class="p-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection