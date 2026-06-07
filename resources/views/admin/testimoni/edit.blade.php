@extends('admin.layouts.app')

@section('title', 'Edit Testimoni - Jeep Dieng')
@section('header_title', 'Edit Testimoni')
@section('header_subtitle', 'Perbarui informasi ulasan pelanggan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-3xl">
    <form action="{{ route('admin.testimoni.update', $testimoni->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Pelanggan <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $testimoni->nama) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Asal Kota</label>
                <input type="text" name="asal_kota" value="{{ old('asal_kota', $testimoni->asal_kota) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pesan Ulasan <span class="text-red-500">*</span></label>
                <textarea name="pesan" rows="4" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">{{ old('pesan', $testimoni->pesan) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Status Penayangan <span class="text-red-500">*</span></label>
                <select name="is_tampil" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                    <option value="1" {{ old('is_tampil', $testimoni->is_tampil) == '1' ? 'selected' : '' }}>Ditampilkan di Beranda</option>
                    <option value="0" {{ old('is_tampil', $testimoni->is_tampil) == '0' ? 'selected' : '' }}>Sembunyikan</option>
                </select>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex gap-4">
            <button type="submit" class="px-8 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Perbarui Testimoni</button>
            <a href="{{ route('admin.testimoni.index') }}" class="px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Batal</a>
        </div>
    </form>
</div>
@endsection
