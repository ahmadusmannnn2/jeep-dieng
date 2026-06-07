@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #BKG-' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT))
@section('header_title', 'Kelola Pesanan Masuk')
@section('header_subtitle', 'Validasi pembayaran dan tugaskan armada Jeep')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="flex justify-between items-start border-b border-gray-100 pb-4 mb-4">
                <div>
                    <h3 class="text-xl font-black text-gray-900">#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Dibuat pada: {{ $pesanan->created_at->translatedFormat('d M Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="block text-xs uppercase text-gray-400 font-bold mb-1">Status Pembayaran</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border 
                        {{ $pesanan->status === 'Lunas' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                        {{ $pesanan->status === 'DP Lunas' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                        {{ $pesanan->status === 'Pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                        {{ $pesanan->status === 'Selesai' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                        {{ $pesanan->status === 'Dibatalkan' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
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

            <div class="grid grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Pelanggan</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->user->name ?? '-' }}</p>
                    <p class="text-gray-500">{{ $pesanan->user->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Paket Wisata</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->paketWisata->nama_paket ?? '-' }}</p>
                    <p class="text-gray-500">{{ $pesanan->komunitas->nama_komunitas ?? 'Umum' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Jadwal Tour</p>
                    <p class="font-bold text-emerald-600">{{ \Carbon\Carbon::parse($pesanan->tanggal_jadwal)->translatedFormat('l, d F Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Jumlah</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->jumlah_pengunjung }} Orang</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <p class="text-gray-400 text-xs font-bold uppercase mb-1">Titik Kumpul / Lokasi Jemput</p>
                <p class="font-bold text-gray-800">{{ $pesanan->titik_jemput }}</p>
            </div>
            
            @if($pesanan->catatan)
            <div class="mt-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Catatan Pesanan & Waktu:</p>
                <p class="text-sm font-medium text-gray-800 whitespace-pre-line">{{ $pesanan->catatan }}</p>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4 mb-4">Histori Pembayaran</h3>
            
            @if($pesanan->pembayarans && $pesanan->pembayarans->count() > 0)
                <div class="space-y-8">
                @foreach($pesanan->pembayarans as $index => $bayar)
                    <div class="flex flex-col md:flex-row gap-6 relative">
                        @if($index > 0)
                            <div class="absolute -top-6 left-0 right-0 border-t border-dashed border-gray-200"></div>
                        @endif
                        <div class="w-full md:w-1/2">
                            <a href="{{ asset('storage/' . $bayar->bukti_bayar) }}" target="_blank" class="block border-2 border-gray-200 rounded-2xl overflow-hidden hover:border-emerald-500 transition relative group">
                                <img src="{{ asset('storage/' . $bayar->bukti_bayar) }}" alt="Bukti Bayar" class="w-full h-auto max-h-64 object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">Klik untuk Perbesar</span>
                                </div>
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 space-y-4 text-sm">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full uppercase tracking-wider">{{ $bayar->jenis_pembayaran }}</span>
                                <span class="text-gray-500 text-xs">{{ $bayar->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-bold uppercase">Bank Pengirim</p>
                                <p class="font-black text-gray-900 text-lg">{{ $bayar->metode_pembayaran }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-bold uppercase">Nominal Dibayar</p>
                                <p class="font-black text-emerald-600 text-2xl">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            @else
                <div class="text-center py-8 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-500 font-medium">Pelanggan belum mengunggah bukti pembayaran manual.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-1 items-start">
        <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST" class="bg-gray-900 rounded-3xl shadow-xl border border-gray-800 p-6 md:p-8 sticky top-6">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-bold text-white border-b border-gray-700 pb-4 mb-6">Penetapan Armada & Status</h3>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ubah Status</label>
                <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-700 bg-gray-800 text-white font-bold focus:ring-2 focus:ring-emerald-500">
                    <option value="Pending" {{ $pesanan->status === 'Pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="DP Lunas" {{ $pesanan->status === 'DP Lunas' ? 'selected' : '' }}>DP Lunas</option>
                    <option value="Lunas" {{ $pesanan->status === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="Selesai" {{ $pesanan->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ $pesanan->status === 'Dibatalkan' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih Kendaraan Jeep</label>
                <select name="jeep_id" class="w-full px-4 py-3 rounded-xl border border-gray-700 bg-gray-800 text-white font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- Belum Ditentukan --</option>
                    @foreach($jeeps as $jp)
                        <option value="{{ $jp->id }}" {{ $pesanan->jeep_id == $jp->id ? 'selected' : '' }}>
                            {{ $jp->nama_jeep }} ({{ $jp->nomor_polisi }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih Supir (Driver)</label>
                <select name="supir_id" class="w-full px-4 py-3 rounded-xl border border-gray-700 bg-gray-800 text-white font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- Belum Ditentukan --</option>
                    @foreach($supirs as $dr)
                        <option value="{{ $dr->id }}" {{ $pesanan->supir_id == $dr->id ? 'selected' : '' }}>
                            {{ $dr->nama_supir }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full py-4 bg-emerald-500 text-white text-base font-extrabold rounded-xl hover:bg-emerald-400 transition shadow-[0_4px_15px_rgba(16,185,129,0.3)]">
                Simpan & Validasi
            </button>
            
            <p class="text-[10px] text-gray-500 text-center mt-4">Jika diubah ke "Lunas" dan armada dipilih, E-Ticket pelanggan otomatis terbit beserta nama Supir.</p>

        </form>
    </div>

</div>
@endsection