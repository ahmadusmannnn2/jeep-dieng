@extends('admin.layouts.app')

@section('title', 'Tambah Jeep - Jeep Dieng')
@section('header_title', 'Tambah Armada Jeep')
@section('header_subtitle', 'Masukkan data kendaraan Jeep baru')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('admin.jeep.store') }}" method="POST" class="space-y-6">
        @csrf
        
        @if(Auth::user()->role === 'super_admin')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Komunitas</label>
            <select name="komunitas_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <option value="">-- Pilih Komunitas --</option>
                @foreach($komunitas as $kom)
                    <option value="{{ $kom->id }}">{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jeep (Tipe/Warna) <span class="text-red-500">*</span></label>
            <input type="text" name="nama_jeep" required placeholder="Contoh: Hardtop Biru" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Polisi <span class="text-red-500">*</span></label>
                <input type="text" name="nomor_polisi" required placeholder="Contoh: AA 1234 BB" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition uppercase">
                @error('nomor_polisi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas (Orang) <span class="text-red-500">*</span></label>
                <input type="number" name="kapasitas" required placeholder="Contoh: 4" value="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status Kendaraan <span class="text-red-500">*</span></label>
            <select name="status" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <option value="Tersedia">Tersedia</option>
                <option value="Disewa">Disewa</option>
                <option value="Perbaikan">Perbaikan (Maintanance)</option>
            </select>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.jeep.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Simpan Data</button>
        </div>
    </form>
</div>
@endsection