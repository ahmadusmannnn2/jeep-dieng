@extends('admin.layouts.app')

@section('title', 'Tambah Supir - Jeep Dieng')
@section('header_title', 'Tambah Data Supir')
@section('header_subtitle', 'Masukkan data supir baru ke dalam sistem')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('admin.supir.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Komunitas</label>
            <select name="komunitas_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <option value="">-- Pilih Komunitas --</option>
                @foreach($komunitas as $kom)
                    <option value="{{ $kom->id }}">{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Supir <span class="text-red-500">*</span></label>
            <input type="text" name="nama_supir" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
            <input type="number" name="no_hp" placeholder="Contoh: 08123456789" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
            <select name="status" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <option value="Tersedia">Tersedia</option>
                <option value="Sedang Bertugas">Sedang Bertugas</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.supir.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Simpan Data</button>
        </div>
    </form>
</div>
@endsection
