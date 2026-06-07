@extends('admin.layouts.app')

@section('title', 'Bagi Hasil Komunitas - Jeep Dieng')
@section('header_title', 'Bagi Hasil Komunitas')
@section('header_subtitle', 'Rekapitulasi pencairan dana untuk masing-masing komunitas penyelenggara')

@section('content')

<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
    <form method="GET" action="{{ route('admin.laporan.komunitas') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ $tanggal_mulai }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition font-medium text-gray-800">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ $tanggal_selesai }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition font-medium text-gray-800">
        </div>
        
        <div>
            <button type="submit" class="w-full px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-emerald-500 transition text-center shadow-md">Terapkan Filter Periode</button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    @php
        $totalSemuaPendapatan = $komunitas->sum('total_pendapatan');
        $totalSemuaTransaksi = $komunitas->sum('total_transaksi');
    @endphp
    
    <div class="md:col-span-2 bg-gradient-to-br from-gray-900 to-gray-800 text-white p-6 rounded-3xl shadow-sm relative overflow-hidden">
        <div class="absolute right-[-10px] bottom-[-10px] text-white/5 transform scale-150">
            <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-1">Total Dana Tersimpan (Kotor)</p>
        <h4 class="text-3xl font-black text-emerald-400">Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}</h4>
        <p class="text-[11px] text-gray-400 mt-2">Akumulasi uang yang harus dicairkan ke seluruh komunitas</p>
    </div>
    
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-center">
        <p class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-1">Total Trip</p>
        <div class="flex items-center gap-3">
            <h4 class="text-3xl font-black text-gray-900">{{ $totalSemuaTransaksi }}</h4>
            <span class="text-sm font-bold text-gray-500 mt-1">Keberangkatan</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @forelse($komunitas as $k)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
        <div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center font-bold mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-lg font-black text-gray-900 mb-1">{{ $k->nama_komunitas }}</h3>
            <p class="text-xs font-bold text-gray-500 mb-4">{{ $k->total_transaksi }} Transaksi Berhasil</p>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Dana untuk Komunitas</p>
            <h4 class="text-2xl font-black text-emerald-600">Rp {{ number_format($k->total_pendapatan, 0, ',', '.') }}</h4>
            
            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.laporan.index', ['komunitas_id' => $k->id, 'tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai]) }}" class="flex-1 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition text-center">Lihat Rincian</a>
                <a href="{{ route('admin.laporan.cetak', ['komunitas_id' => $k->id, 'tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai]) }}" target="_blank" class="px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-xl transition" title="Cetak Tagihan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="lg:col-span-3 text-center py-10">
        <p class="text-gray-400 font-medium">Belum ada data komunitas penyelenggara.</p>
    </div>
    @endforelse
</div>

@endsection
