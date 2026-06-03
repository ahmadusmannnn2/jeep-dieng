@extends('admin.layouts.app')

@section('title', 'Tulis Artikel - Jeep Dieng')
@section('header_title', 'Tulis Artikel/Promo Baru')
@section('header_subtitle', 'Terbitkan informasi menarik untuk pengunjung')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-3xl mx-auto">
    <form action="{{ route('admin.konten-informasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Artikel <span class="text-red-500">*</span></label>
            <input type="text" name="judul" required placeholder="Contoh: Diskon 20% Akhir Tahun" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publish <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_publish" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar (Opsional)</label>
                <input type="file" name="gambar" accept="image/*" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Isi Konten <span class="text-red-500">*</span></label>
            <textarea name="isi" rows="6" required placeholder="Tuliskan detail informasi di sini..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"></textarea>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.konten-informasi.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Terbitkan</button>
        </div>
    </form>
</div>
@endsection