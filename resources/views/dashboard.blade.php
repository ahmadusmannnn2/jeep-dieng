@extends('frontend.layouts.app')

@section('title', 'Dasbor Saya - Jeep Dieng')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Pesanan</h2>
        <p class="text-gray-500 mt-2">Hai, {{ Auth::user()->name }}! Berikut adalah daftar pemesanan paket wisata Jeep Anda.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl shadow-sm mb-6 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800 text-lg">Riwayat Pesanan</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($pesanan as $item)
                <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-gray-50 transition">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-gray-200 text-gray-700">#BKG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                            
                            @if($item->status === 'Pending')
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">Menunggu Persetujuan Admin</span>
                            @elseif($item->status === 'Disetujui')
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Menunggu Pembayaran</span>
                            @elseif($item->status === 'Lunas')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Lunas (Siap Berangkat)</span>
                            @elseif($item->status === 'Selesai')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Tour Selesai</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Dibatalkan</span>
                            @endif
                        </div>
                        <h4 class="font-bold text-gray-900 text-lg mb-1">{{ $item->paketWisata->nama_paket ?? 'Paket Terhapus' }}</h4>
                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal: <span class="font-semibold text-gray-800">{{ $item->jadwal ? \Carbon\Carbon::parse($item->jadwal->tanggal)->translatedFormat('l, d F Y') . ' jam ' . \Carbon\Carbon::parse($item->jadwal->jam)->format('H:i') : '-' }}</span>
                        </p>
                        <p class="text-sm text-gray-500">Total Tagihan: <span class="font-bold text-emerald-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span></p>
                    </div>

                    <div class="shrink-0 flex flex-col gap-2">
                        @if($item->status === 'Disetujui')
                            <a href="{{ route('booking.payment', $item->id) }}" class="px-5 py-2.5 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition text-center shadow-lg shadow-emerald-500/30">Upload Bukti Bayar</a>
                        @endif
                        <a href="{{ route('booking.show', $item->id) }}" class="px-5 py-2.5 bg-gray-950 text-white hover:bg-emerald-500 font-bold rounded-xl transition text-center shadow-md">Lihat Detail Tiket</a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500">
                    <p class="mb-4">Anda belum memiliki riwayat pemesanan.</p>
                    <a href="{{ route('home') }}#paket" class="inline-block px-6 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition">Lihat Paket Wisata</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection