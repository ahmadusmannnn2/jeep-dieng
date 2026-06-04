@extends('admin.layouts.app')

@section('title', 'Pengaturan Umum - Jeep Dieng')
@section('header_title', 'Pengaturan Website')
@section('header_subtitle', 'Kelola identitas merek, logo, dan informasi kontak publik')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-4xl mx-auto">
    <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-6">Identitas Merek (Branding)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Website</label>
                    <input type="text" name="nama_website" value="{{ $pengaturan->nama_website }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Logo Website Saat Ini</label>
                    <div class="flex items-center gap-4 mb-3">
                        @if($pengaturan->logo)
                            <div class="w-16 h-16 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-center overflow-hidden p-2">
                                <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                            </div>
                        @else
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center font-bold text-xs text-center border border-emerald-100">Belum<br>Ada</div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-1">*Upload gambar baru untuk mengganti logo lama. (Format: PNG transparan direkomendasikan, max 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-6">Informasi Kontak Publik & Footer</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon (CS)</label>
                    <input type="text" name="no_telp" value="{{ $pengaturan->no_telp }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Publik</label>
                    <input type="email" name="email" value="{{ $pengaturan->email }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Kantor/Basecamp</label>
                <input type="text" name="alamat" value="{{ $pengaturan->alamat }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat (Tampil di Footer Website)</label>
                <textarea name="deskripsi_footer" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">{{ $pengaturan->deskripsi_footer }}</textarea>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-8 py-3.5 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Simpan Pengaturan</button>
        </div>
    </form>
</div>
@endsection