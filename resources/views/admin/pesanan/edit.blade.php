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

            <!-- PENUGASAN MULTI-ARMADA -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider">Penugasan Armada & Supir</label>
                    <button type="button" id="btn-tambah-armada" class="px-4 py-2 bg-emerald-100 text-emerald-700 font-bold rounded-lg text-xs hover:bg-emerald-200 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Armada
                    </button>
                </div>
                
                <div id="armada-wrapper" class="space-y-4">
                    @if($pesanan->armadas->count() > 0)
                        @foreach($pesanan->armadas as $index => $armada)
                        <div class="armada-row flex gap-4 p-4 bg-gray-50 border border-gray-200 rounded-xl relative">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Jeep</label>
                                <select name="jeep_id[]" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-emerald-500 text-sm">
                                    <option value="">-- Kosongkan / Hapus --</option>
                                    @foreach($jeeps as $j)
                                        <option value="{{ $j->id }}" {{ $armada->jeep_id == $j->id ? 'selected' : '' }}>{{ $j->nama_jeep }} ({{ $j->nomor_polisi }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Supir</label>
                                <select name="supir_id[]" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-emerald-500 text-sm">
                                    <option value="">-- Kosongkan / Hapus --</option>
                                    @foreach($supirs as $s)
                                        <option value="{{ $s->id }}" {{ $armada->supir_id == $s->id ? 'selected' : '' }}>{{ $s->nama_supir }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn-hapus-armada absolute top-[-10px] right-[-10px] bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <!-- Form Kosong Pertama Jika Belum Ada -->
                        <div class="armada-row flex gap-4 p-4 bg-gray-50 border border-gray-200 rounded-xl relative">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Jeep</label>
                                <select name="jeep_id[]" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-emerald-500 text-sm">
                                    <option value="">-- Kosongkan --</option>
                                    @foreach($jeeps as $j)
                                        <option value="{{ $j->id }}">{{ $j->nama_jeep }} ({{ $j->nomor_polisi }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Supir</label>
                                <select name="supir_id[]" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-emerald-500 text-sm">
                                    <option value="">-- Kosongkan --</option>
                                    @foreach($supirs as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama_supir }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn-hapus-armada absolute top-[-10px] right-[-10px] bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-3 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Sistem akan mengabaikan (tidak menyimpan) baris armada jika pilihan dikosongkan.
                </p>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="flex-1 px-6 py-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Template HTML untuk Kloning via JavaScript -->
<div id="armada-template" class="hidden">
    <div class="armada-row flex gap-4 p-4 bg-gray-50 border border-emerald-300 rounded-xl relative mt-4">
        <div class="flex-1">
            <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Jeep</label>
            <select name="jeep_id[]" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-emerald-500 text-sm">
                <option value="">-- Kosongkan --</option>
                @foreach($jeeps as $j)
                    <option value="{{ $j->id }}">{{ $j->nama_jeep }} ({{ $j->nomor_polisi }})</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Supir</label>
            <select name="supir_id[]" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-emerald-500 text-sm">
                <option value="">-- Kosongkan --</option>
                @foreach($supirs as $s)
                    <option value="{{ $s->id }}">{{ $s->nama_supir }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" class="btn-hapus-armada absolute top-[-10px] right-[-10px] bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const wrapper = document.getElementById('armada-wrapper');
        const btnTambah = document.getElementById('btn-tambah-armada');
        const template = document.getElementById('armada-template').innerHTML;

        // Fungsi Tambah Row
        btnTambah.addEventListener('click', function() {
            wrapper.insertAdjacentHTML('beforeend', template);
        });

        // Fungsi Hapus Row (Event Delegation)
        wrapper.addEventListener('click', function(e) {
            if (e.target.closest('.btn-hapus-armada')) {
                const row = e.target.closest('.armada-row');
                row.remove();
            }
        });
    });
</script>
@endsection