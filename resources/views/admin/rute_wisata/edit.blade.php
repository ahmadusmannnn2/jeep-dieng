@extends('admin.layouts.app')

@section('title', 'Edit Rute Wisata - Jeep Dieng')
@section('header_title', 'Edit Rute Wisata')
@section('header_subtitle', 'Perbarui detail destinasi perjalanan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('admin.rute-wisata.update', $ruteWisata->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        @if(Auth::user()->role === 'super_admin')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Komunitas</label>
            <select name="komunitas_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <option value="">-- Pilih Komunitas --</option>
                @foreach($komunitas as $kom)
                    <option value="{{ $kom->id }}" {{ $ruteWisata->komunitas_id == $kom->id ? 'selected' : '' }}>{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Rute (Titik Kunjungan) <span class="text-red-500">*</span></label>
            <input type="text" name="nama_rute" value="{{ $ruteWisata->nama_rute }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Rute</label>
            <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">{{ $ruteWisata->deskripsi }}</textarea>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.rute-wisata.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Perbarui Data</button>
        </div>
    </form>
</div>
@endsection