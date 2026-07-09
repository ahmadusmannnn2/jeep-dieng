@extends('admin.layouts.app')

@section('title', 'Tambah Komunitas')
@section('header_title', 'Tambah Komunitas Baru')
@section('header_subtitle', 'Daftarkan mitra komunitas beserta akun login pengelola')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.komunitas.store') }}" method="POST" class="p-6 md:p-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h3 class="text-lg font-black text-gray-900 border-b pb-2">Data Komunitas</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Komunitas <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_komunitas" value="{{ old('nama_komunitas') }}" required placeholder="Contoh: Komunitas Arjuna Dieng" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('nama_komunitas') border-red-500 @enderror">
                        @error('nama_komunitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ketua / Penanggung Jawab</label>
                        <input type="text" name="ketua" value="{{ old('ketua') }}" placeholder="Nama Ketua" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon / WA</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Sekretariat</label>
                        <textarea name="alamat" rows="3" placeholder="Alamat lengkap basecamp..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">{{ old('alamat') }}</textarea>
                    </div>
                </div>

                <div class="space-y-6 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <div>
                        <h3 class="text-lg font-black text-emerald-600 border-b border-emerald-100 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            Akun Login Pengelola
                        </h3>
                        <p class="text-xs text-gray-500 mt-2">Akun ini akan digunakan oleh ketua komunitas untuk login ke dashboard.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="pengelola@gmail.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('password') border-red-500 @enderror">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Informasi Rekening (New Section for Withdrawal) -->
                <div class="space-y-6 lg:col-span-2 mt-4 bg-indigo-50 p-6 rounded-2xl border border-indigo-100">
                    <div>
                        <h3 class="text-lg font-black text-indigo-700 border-b border-indigo-200 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Informasi Rekening (Untuk Pencairan Saldo)
                        </h3>
                        <p class="text-sm text-indigo-600 mt-2">Opsional. Dapat diisi nanti, namun diperlukan agar proses penarikan saldo ke depannya dapat berjalan lancar.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Bank</label>
                            <input type="text" name="nama_bank" placeholder="Contoh: BCA, BRI, Mandiri" value="{{ old('nama_bank') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Rekening</label>
                            <input type="text" name="no_rekening" placeholder="Contoh: 1234567890" value="{{ old('no_rekening') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Atas Nama</label>
                            <input type="text" name="atas_nama" placeholder="Contoh: Budi Santoso" value="{{ old('atas_nama') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.komunitas.index') }}" class="px-6 py-3 text-gray-500 font-medium hover:bg-gray-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-8 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
                    Simpan Komunitas & Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection