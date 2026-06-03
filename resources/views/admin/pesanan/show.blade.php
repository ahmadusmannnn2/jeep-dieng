@extends('admin.layouts.app')

@section('title', 'Detail Pesanan - Jeep Dieng')
@section('header_title', 'Detail Pesanan #BKG-' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT))
@section('header_subtitle', 'Informasi lengkap pemesanan dan pembayaran')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h4 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Trip</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 mb-1">Paket Wisata</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->paketWisata->nama_paket ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Jadwal Keberangkatan</p>
                    <p class="font-bold text-gray-800">
                        {{ $pesanan->jadwal ? \Carbon\Carbon::parse($pesanan->jadwal->tanggal)->translatedFormat('l, d F Y') . ' - ' . \Carbon\Carbon::parse($pesanan->jadwal->jam)->format('H:i') . ' WIB' : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Jumlah Pengunjung</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->jumlah_pengunjung }} Orang</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Total Harga</p>
                    <p class="font-bold text-emerald-600 text-lg">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500 mb-1">Catatan Tambahan</p>
                    <p class="font-medium text-gray-800">{{ $pesanan->catatan ?? 'Tidak ada catatan.' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h4 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Penugasan Armada</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 mb-1">Jeep yang Digunakan</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->jeep->nama_jeep ?? 'Belum Ditugaskan' }} <span class="text-xs font-normal text-gray-500">({{ $pesanan->jeep->nomor_polisi ?? '-' }})</span></p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Supir Bertugas</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->supir->nama_supir ?? 'Belum Ditugaskan' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                <h4 class="text-lg font-bold text-gray-800">Status & Customer</h4>
                <a href="{{ route('admin.pesanan.edit', $pesanan->id) }}" class="text-sm font-bold text-emerald-500 hover:text-emerald-600">Update Status</a>
            </div>
            
            <div class="mb-4 text-center p-3 rounded-xl {{ $pesanan->status === 'Lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                <span class="block text-xs uppercase tracking-wider mb-1">Status Saat Ini</span>
                <span class="text-lg font-bold">{{ $pesanan->status }}</span>
            </div>

            <div class="text-sm">
                <p class="text-gray-500 mb-1">Nama Pemesan</p>
                <p class="font-bold text-gray-800 mb-3">{{ $pesanan->user->name ?? '-' }}</p>
                
                <p class="text-gray-500 mb-1">Nomor WhatsApp</p>
                <p class="font-bold text-gray-800 mb-3">{{ $pesanan->user->no_hp ?? '-' }}</p>
                
                <p class="text-gray-500 mb-1">Komunitas Penyelenggara</p>
                <p class="font-bold text-gray-800">{{ $pesanan->komunitas->nama_komunitas ?? '-' }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h4 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Bukti Pembayaran</h4>
            
            @if($pesanan->pembayaran)
                <div class="text-sm mb-4">
                    <p class="text-gray-500 mb-1">Metode Transfer</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->pembayaran->metode_pembayaran }}</p>
                </div>
                @if($pesanan->pembayaran->bukti_bayar)
                    <a href="{{ asset('storage/' . $pesanan->pembayaran->bukti_bayar) }}" target="_blank" class="block w-full text-center py-2 bg-gray-900 text-white rounded-xl text-sm font-medium hover:bg-emerald-500 transition shadow-md">Lihat Bukti Foto</a>
                @else
                    <p class="text-sm text-amber-500 font-medium">Customer belum mengunggah bukti foto.</p>
                @endif
            @else
                <p class="text-sm text-gray-500 text-center py-4">Belum ada data pembayaran masuk.</p>
            @endif
        </div>
    </div>
</div>
@endsection