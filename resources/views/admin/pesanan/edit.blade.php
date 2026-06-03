@extends('admin.layouts.app')

@section('title', 'Update Pesanan - Jeep Dieng')
@section('header_title', 'Update Status Pesanan')
@section('header_subtitle', 'Ubah status dan tugaskan armada untuk pesanan #BKG-' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status Pesanan Saat Ini <span class="text-red-500">*</span></label>
            <select name="status" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition font-bold text-gray-800">
                <option value="Pending" {{ $pesanan->status == 'Pending' ? 'selected' : '' }}>Pending (Menunggu Persetujuan)</option>
                <option value="Disetujui" {{ $pesanan->status == 'Disetujui' ? 'selected' : '' }}>Disetujui (Menunggu Pembayaran Customer)</option>
                <option value="Lunas" {{ $pesanan->status == 'Lunas' ? 'selected' : '' }}>Lunas (Pembayaran Valid)</option>
                <option value="Selesai" {{ $pesanan->status == 'Selesai' ? 'selected' : '' }}>Selesai (Tour Telah Berakhir)</option>
                <option value="Dibatalkan" {{ $pesanan->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-50 rounded-2xl border border-gray-100">
            <div class="col-span-full">
                <h5 class="font-bold text-gray-800 text-sm mb-1">Penugasan Armada (Opsional)</h5>
                <p class="text-xs text-gray-500">Pilih Jeep dan Supir yang akan berangkat. Hanya menampilkan yang terdaftar di komunitas ini.</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jeep</label>
                <select name="jeep_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    <option value="">-- Belum Ditugaskan --</option>
                    @foreach($jeeps as $jeep)
                        <option value="{{ $jeep->id }}" {{ $pesanan->jeep_id == $jeep->id ? 'selected' : '' }}>
                            {{ $jeep->nama_jeep }} ({{ $jeep->nomor_polisi }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Supir</label>
                <select name="supir_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    <option value="">-- Belum Ditugaskan --</option>
                    @foreach($supirs as $supir)
                        <option value="{{ $supir->id }}" {{ $pesanan->supir_id == $supir->id ? 'selected' : '' }}>
                            {{ $supir->nama_supir }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection