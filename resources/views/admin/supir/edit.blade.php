@extends('admin.layouts.app')

@section('title', 'Edit Supir - Jeep Dieng')
@section('header_title', 'Edit Data Supir')
@section('header_subtitle', 'Perbarui informasi supir')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('admin.supir.update', $supir->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        @if(Auth::user()->role === 'super_admin')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Komunitas</label>
            <select name="komunitas_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <option value="">-- Pilih Komunitas --</option>
                @foreach($komunitas as $kom)
                    <option value="{{ $kom->id }}" {{ $supir->komunitas_id == $kom->id ? 'selected' : '' }}>{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Supir <span class="text-red-500">*</span></label>
            <input type="text" name="nama_supir" value="{{ $supir->nama_supir }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
            <input type="number" name="no_hp" value="{{ $supir->no_hp }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
            <select name="status" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <option value="Tersedia" {{ $supir->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Sedang Bertugas" {{ $supir->status == 'Sedang Bertugas' ? 'selected' : '' }}>Sedang Bertugas</option>
                <option value="Tidak Aktif" {{ $supir->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.supir.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Perbarui Data</button>
        </div>
    </form>
</div>
@endsection