@extends('admin.layouts.app')

@section('title', 'Laporan Keuangan - Jeep Dieng')
@section('header_title', 'Laporan Keuangan')
@section('header_subtitle', 'Rekapitulasi total omzet dan riwayat pemesanan sukses')

@section('content')

<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ $tanggal_mulai }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition font-medium text-gray-800">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ $tanggal_selesai }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition font-medium text-gray-800">
        </div>
        
        @if(Auth::user()->role !== 'pengelola')
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Komunitas</label>
            <select name="komunitas_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition font-bold text-gray-800">
                <option value="">-- Semua Komunitas --</option>
                @foreach($daftar_komunitas as $kom)
                    <option value="{{ $kom->id }}" {{ $komunitas_id == $kom->id ? 'selected' : '' }}>{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="flex gap-2">
            <button type="submit" class="flex-1 px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-emerald-500 transition text-center shadow-md">Filter Data</button>
            
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.laporan.export_excel', ['tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai, 'komunitas_id' => $komunitas_id]) }}" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20" title="Export data ke Microsoft Excel (CSV)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                Export Excel
            </a>

            <a href="{{ route('admin.laporan.cetak', ['tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai, 'komunitas_id' => $komunitas_id]) }}" target="_blank" class="px-5 py-2.5 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </a>
            @endif
        </div>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white p-6 rounded-3xl shadow-sm">
        <p class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-1">Total Pendapatan Bersih</p>
        <h4 class="text-3xl font-black text-emerald-400">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</h4>
    </div>
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-1">Total Wisatawan Berangkat</p>
            <h4 class="text-3xl font-black text-gray-900">{{ $total_transaksi }} Trip</h4>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center font-bold">✓</div>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-100 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Tanggal Order</th>
                    <th class="px-6 py-4">Kode Booking</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Penyelenggara</th>
                    <th class="px-6 py-4">Paket Wisata</th>
                    <th class="px-6 py-4 text-right">Terbayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($laporan as $item)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">#BKG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-medium">{{ $item->user->name }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs font-medium">{{ $item->komunitas->nama_komunitas ?? 'Umum' }}</span></td>
                    <td class="px-6 py-4 text-gray-600">{{ $item->paketWisata->nama_paket ?? 'Paket Terhapus' }}</td>
                    <td class="px-6 py-4 text-right font-black text-gray-900">
                        Rp {{ number_format($item->pembayarans->where('status', 'Valid')->sum('jumlah_bayar'), 0, ',', '.') }}
                        @if($item->status === 'DP Lunas')
                            <br><span class="text-[10px] text-amber-600 font-bold">(DP 50%)</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ditemukan transaksi sukses pada rentang tanggal terpilih.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
