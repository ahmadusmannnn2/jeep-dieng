@extends('frontend.layouts.app')

@section('title', 'Detail Tiket #BKG-' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="mb-6 flex items-center gap-2">
        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-emerald-500 flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dasbor
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 flex flex-col md:flex-row">
        
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
                        {{ $pesanan->status === 'Pending' ? 'bg-gray-100 text-gray-700' : '' }}
                        {{ $pesanan->status === 'Disetujui' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $pesanan->status === 'Selesai' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $pesanan->status === 'Dibatalkan' ? 'bg-red-100 text-red-700' : '' }}
                    ">
                        {{ $pesanan->status }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-y-6 gap-x-4 text-sm border-t border-b border-gray-100 py-6 my-6">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Nama Penumpang</p>
                    <p class="font-extrabold text-gray-800 text-base">{{ $pesanan->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Kode Reservasi</p>
                    <p class="font-extrabold text-gray-900 text-base tracking-wider">#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Tanggal & Waktu Trip</p>
                    <p class="font-bold text-gray-800">
                        {{ $pesanan->jadwal ? \Carbon\Carbon::parse($pesanan->jadwal->tanggal)->translatedFormat('l, d M Y') : '-' }}
                        <span class="block text-xs text-emerald-600 font-black mt-0.5">{{ $pesanan->jadwal ? \Carbon\Carbon::parse($pesanan->jadwal->jam)->format('H:i') . ' WIB' : '-' }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Jumlah Peserta</p>
                    <p class="font-bold text-gray-800 text-base">{{ $pesanan->jumlah_pengunjung }} Orang <span class="text-xs text-gray-400 font-normal">(1 Armada Jeep)</span></p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <h4 class="font-black text-gray-900 text-sm mb-3 uppercase tracking-wider text-emerald-500">Informasi Armada & Driver</h4>
                @if($pesanan->jeep_id && $pesanan->supir_id)
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-400 text-xs font-bold mb-0.5">Kendaran Jeep</p>
                            <p class="font-bold text-gray-800">{{ $pesanan->jeep->nama_jeep }}</p>
                            <p class="text-xs font-black text-gray-900 mt-1 px-2 py-0.5 bg-gray-200 inline-block rounded">{{ $pesanan->jeep->nomor_polisi }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs font-bold mb-0.5">Nama Supir (Driver)</p>
                            <p class="font-bold text-gray-800 mb-1">{{ $pesanan->supir->nama_supir }}</p>
                            <p class="text-xs font-medium text-gray-500">Kapasitas Maks: {{ $pesanan->jeep->kapasitas }} Orang</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Armada Jeep dan Supir sedang dipersiapkan oleh pihak pengelola komunitas.</p>
                @endif
            </div>
            
            @if($pesanan->catatan)
            <div class="mt-4 text-xs text-gray-500 bg-amber-50 p-3 rounded-xl border border-amber-100">
                <span class="font-bold text-amber-700">Catatan Anda:</span> "{{ $pesanan->catatan }}"
            </div>
            @endif
        </div>

        <div class="bg-gray-900 text-white p-8 md:p-10 md:w-80 flex flex-col justify-between items-center text-center relative border-t-2 md:border-t-0 md:border-l-2 border-dashed border-gray-700">
            
            <div class="hidden md:block absolute top-[-12px] left-[-12px] w-6 h-6 bg-gray-50 rounded-full"></div>
            <div class="hidden md:block absolute bottom-[-12px] left-[-12px] w-6 h-6 bg-gray-50 rounded-full"></div>

            <div class="w-full">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-1">Total Biaya Trip</p>
                <h4 class="text-3xl font-black text-emerald-400">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h4>
                <p class="text-[10px] text-gray-400 mt-1">*Tarif flat sewa per kendaraan Jeep</p>
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
                <p>Tunjukkan halaman e-tiket digital ini kepada pengelola/supir di lokasi titik kumpul (*meeting point*).</p>
            </div>
        </div>

    </div>

</div>
@endsection