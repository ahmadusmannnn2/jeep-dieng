@extends('admin.layouts.app')

@section('title', 'Edit Komunitas')
@section('header_title', 'Edit Data Komunitas')
@section('header_subtitle', 'Perbarui informasi mitra komunitas dan akses loginnya')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.komunitas.update', $komunitas->id) }}" method="POST" class="p-6 md:p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h3 class="text-lg font-black text-gray-900 border-b pb-2">Data Komunitas</h3>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Komunitas <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_komunitas" value="{{ old('nama_komunitas', $komunitas->nama_komunitas) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('nama_komunitas') border-red-500 @enderror">
                        @error('nama_komunitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ketua / Penanggung Jawab</label>
                        <input type="text" name="ketua" value="{{ old('ketua', $komunitas->ketua) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon / WA</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $komunitas->no_hp) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Sekretariat</label>
                        <textarea name="alamat" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">{{ old('alamat', $komunitas->alamat) }}</textarea>
                    </div>
                </div>

                <div class="space-y-6 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <div>
                        <h3 class="text-lg font-black text-emerald-600 border-b border-emerald-100 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            Akun Login Pengelola
                        </h3>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $pengelola->email ?? '') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Reset Password</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('password') border-red-500 @enderror">
                        <p class="text-[11px] text-gray-500 mt-2">Hanya isi form password ini jika Anda ingin mengganti/mereset sandi pengelola.</p>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.komunitas.index') }}" class="px-6 py-3 text-gray-500 font-medium hover:bg-gray-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-8 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection