@extends('admin.layouts.app')

@section('title', 'Edit Komunitas')
@section('header_title', 'Edit Data Komunitas')
@section('header_subtitle', 'Perbarui informasi mitra komunitas')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.komunitas.update', $komunitas->id) }}" method="POST" class="p-6 md:p-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Nama Komunitas -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Komunitas <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_komunitas" value="{{ old('nama_komunitas', $komunitas->nama_komunitas) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('nama_komunitas') border-red-500 @enderror">
                    @error('nama_komunitas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ketua -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ketua / Penanggung Jawab</label>
                        <input type="text" name="ketua" value="{{ old('ketua', $komunitas->ketua) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>

                    <!-- No HP -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon / WA</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $komunitas->no_hp) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Sekretariat</label>
                    <textarea name="alamat" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">{{ old('alamat', $komunitas->alamat) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.komunitas.index') }}" class="px-6 py-3 text-gray-500 font-medium hover:bg-gray-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-8 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
