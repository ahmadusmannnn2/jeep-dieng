@extends('frontend.layouts.app')

@section('title', 'Detail Tiket #BKG-' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-emerald-500 flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dasbor
        </a>
        
        <a href="{{ route('booking.print', $pesanan->id) }}" target="_blank" class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-emerald-500 transition shadow-md flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Download Tiket (PDF)
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 flex flex-col md:flex-row relative">
        
        <div class="flex-1 p-8 md:p-10">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="text-xs uppercase tracking-widest font-bold text-gray-400">E-Ticket Resmi</span>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $pesanan->paketWisata->nama_paket ?? 'Paket Wisata' }}</h3>
                    <p class="text-sm text-emerald-600 font-bold mt-1">{{ $pesanan->komunitas->nama_komunitas ?? '-' }}</p>
                </div>
                
                <div class="text-right">
                    <span class="block text-xs font-bold text-gray-400 uppercase">Status</span>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-black
                        {{ $pesanan->status === 'Lunas' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $pesanan->status === 'Pending' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $pesanan->status === 'DP Lunas' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $pesanan->status === 'Selesai' ? 'bg-indigo-100 text-indigo-700' : '' }}
                        {{ $pesanan->status === 'Dibatalkan' ? 'bg-red-100 text-red-700' : '' }}
                    ">
                        @if($pesanan->status === 'Pending')
                            Menunggu Pembayaran
                        @elseif($pesanan->status === 'Dibatalkan')
                            Batal
                        @else
                            {{ $pesanan->status }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-y-6 gap-x-4 text-sm border-t border-gray-100 pt-6 mt-6">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Nama Pemesan</p>
                    <p class="font-extrabold text-gray-800 text-base">{{ $pesanan->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Tipe Trip</p>
                    <p class="font-extrabold text-emerald-600 text-base tracking-wider">{{ $pesanan->tipe_trip ?? 'Private' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Tanggal Tour</p>
                    <p class="font-bold text-gray-800 text-base">
                        {{ \Carbon\Carbon::parse($pesanan->tanggal_jadwal)->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Peserta & Armada</p>
                    <p class="font-bold text-gray-800 text-base">{{ $pesanan->jumlah_pengunjung }} Orang <span class="text-xs text-gray-400 font-normal">({{ $pesanan->jumlah_jeep }} Jeep)</span></p>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-100 pt-6">
                <p class="text-gray-400 text-xs font-bold uppercase mb-2">Lokasi Penjemputan (Titik Kumpul)</p>
                <p class="font-extrabold text-gray-900 text-lg flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $pesanan->titik_jemput }}
                </p>
                
                @if($pesanan->catatan)
                <div class="mt-4 text-sm text-gray-700 bg-amber-50 p-4 rounded-xl border border-amber-100 whitespace-pre-line leading-relaxed">
                    <span class="font-bold text-amber-800 flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Waktu Jemput & Catatan:
                    </span> 
                    {{ $pesanan->catatan }}
                </div>
                @endif
            </div>

            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 mt-6">
                <h4 class="font-black text-gray-900 text-sm mb-4 uppercase tracking-wider text-emerald-500">Informasi Armada & Driver</h4>
                @if(isset($pesanan->armadas) && $pesanan->armadas->count() > 0)
                    <div class="space-y-4">
                        @foreach($pesanan->armadas as $index => $armada)
                            <div class="p-3 bg-white border border-gray-200 rounded-xl">
                                <span class="text-[10px] font-black uppercase text-emerald-600 tracking-wider mb-2 block">Armada {{ $index + 1 }}</span>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-400 text-xs font-bold mb-0.5">Kendaraan Jeep</p>
                                        <p class="font-bold text-gray-800">{{ $armada->jeep->nama_jeep ?? 'Data Dihapus' }}</p>
                                        <p class="text-xs font-black text-gray-900 mt-1 px-2 py-0.5 bg-gray-200 inline-block rounded">{{ $armada->jeep->nomor_polisi ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-xs font-bold mb-0.5">Nama Supir</p>
                                        <p class="font-bold text-gray-800 mb-1">{{ $armada->supir->nama_supir ?? 'Data Dihapus' }}</p>
                                        <p class="text-xs font-medium text-gray-500">No. HP: {{ $armada->supir->no_hp ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Armada Jeep dan Supir akan diinformasikan menjelang hari keberangkatan.
                    </p>
                @endif
            </div>

            <div class="mt-8 border-t border-gray-100 pt-8">
                <h4 class="font-black text-gray-900 text-lg mb-4">Riwayat Pembayaran</h4>
                @if($pesanan->pembayarans && $pesanan->pembayarans->count() > 0)
                    <div class="space-y-4">
                    @foreach($pesanan->pembayarans as $bayar)
                        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 flex flex-col md:flex-row justify-between md:items-center gap-4">
                            <div>
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded uppercase tracking-wider mb-2 inline-block">{{ $bayar->jenis_pembayaran }}</span>
                                <p class="font-bold text-gray-900">
                                    {{ $bayar->metode_pembayaran }}
                                    @if($bayar->payment_channel)
                                        <span class="text-xs font-semibold text-gray-500">({{ strtoupper($bayar->payment_channel) }})</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 font-medium">{{ $bayar->created_at->translatedFormat('d F Y - H:i') }}</p>
                            </div>
                            <div class="md:text-right">
                                <p class="font-black text-emerald-600 text-xl">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</p>
                                <span class="text-[11px] font-bold uppercase tracking-wider {{ $bayar->status === 'Valid' ? 'text-emerald-500' : 'text-amber-500' }} mt-1 inline-block">{{ $bayar->status }}</span>
                            </div>
                        </div>
                    @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic p-4 bg-gray-50 rounded-xl border border-gray-100">Belum ada riwayat pembayaran.</p>
                @endif
            </div>
            
        </div>

        <div class="bg-gray-900 text-white p-8 md:p-10 md:w-80 flex flex-col justify-between items-center text-center relative border-t-2 md:border-t-0 md:border-l-2 border-dashed border-gray-700">
            
            <div class="hidden md:block absolute top-[-12px] left-[-12px] w-6 h-6 bg-gray-50 rounded-full"></div>
            <div class="hidden md:block absolute bottom-[-12px] left-[-12px] w-6 h-6 bg-gray-50 rounded-full"></div>

            <div class="w-full">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Total Tagihan Trip</p>
                <h4 class="text-3xl font-black text-emerald-400">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h4>
                <p class="text-[10px] text-gray-400 mt-1">*Tarif Rp {{ number_format($pesanan->paketWisata->harga, 0, ',', '.') }} / Jeep ({{ $pesanan->jumlah_jeep }} Kendaraan)</p>
            </div>

            <div class="my-8 p-4 bg-white rounded-2xl inline-block shadow-lg shadow-emerald-500/5">
                <div class="w-32 h-32 bg-gray-900 flex flex-col items-center justify-center p-2 rounded-xl border-4 border-gray-900">
                    <div class="grid grid-cols-2 gap-2 w-full h-full opacity-80 bg-[url('https://www.transparenttextures.com/patterns/black-twill.png')]">
                        <div class="border-2 border-white w-6 h-6"></div>
                        <div class="w-6 h-6 self-end justify-self-end border-2 border-white"></div>
                        <div class="w-6 h-6 border-2 border-white"></div>
                        <div class="w-6 h-6 bg-emerald-400 rounded-sm"></div>
                    </div>
                </div>
                <span class="block text-[10px] text-gray-500 font-bold mt-2 tracking-widest">PIN: {{ 202600 + $pesanan->id }}</span>
            </div>

            <div class="w-full text-xs text-gray-400">
                <p class="font-bold text-white mb-1">Metode Verifikasi</p>
                <p>Tunjukkan halaman e-tiket digital ini kepada pengelola/supir di lokasi titik kumpul.</p>
            </div>
        </div>

    </div>

</div>
@endsection