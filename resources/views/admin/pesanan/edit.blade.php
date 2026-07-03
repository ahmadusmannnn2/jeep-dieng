@extends('admin.layouts.app')

@section('title', 'Kelola Pesanan - Jeep Dieng')
@section('header_title', 'Kelola Pesanan #BKG-' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT))
@section('header_subtitle', 'Tugaskan armada dan perbarui status pesanan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.pesanan.index') }}" class="text-sm font-bold text-gray-500 hover:text-emerald-500 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Pesanan
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-8">
        <div class="border-b border-gray-100 pb-6 mb-6">
            <h3 class="text-xl font-black text-gray-900">Informasi Pemesan</h3>
            <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                <div>
                    <p class="text-gray-500 font-bold mb-1">Nama Customer</p>
                    <p class="font-semibold text-gray-900">{{ $pesanan->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold mb-1">Tipe & Jumlah Peserta</p>
                    <p class="font-semibold text-gray-900">{{ $pesanan->tipe_trip ?? 'Private' }} - {{ $pesanan->jumlah_pengunjung }} Orang</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- STATUS PESANAN -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Status Pesanan</label>
                <select name="status" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 font-bold text-gray-900 transition bg-gray-50">
                    <option value="Pending" {{ $pesanan->status == 'Pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran)</option>
                    <option value="Disetujui" {{ $pesanan->status == 'Disetujui' ? 'selected' : '' }}>Disetujui (Silakan Bayar)</option>
                    <option value="DP Lunas" {{ $pesanan->status == 'DP Lunas' ? 'selected' : '' }}>DP Lunas</option>
                    <option value="Lunas" {{ $pesanan->status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="Selesai" {{ $pesanan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ $pesanan->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.pesanan.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="px-8 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection