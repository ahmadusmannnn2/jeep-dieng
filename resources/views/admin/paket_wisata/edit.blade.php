@extends('admin.layouts.app')

@section('title', 'Edit Paket Wisata - Jeep Dieng')
@section('header_title', 'Edit Paket Wisata')
@section('header_subtitle', 'Perbarui detail paket wisata')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('admin.paket-wisata.update', $paketWisata->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        @if(Auth::user()->role === 'super_admin')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Komunitas</label>
            <select name="komunitas_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">-- Pilih Komunitas --</option>
                @foreach($komunitas as $kom)
                    <option value="{{ $kom->id }}" {{ $paketWisata->komunitas_id == $kom->id ? 'selected' : '' }}>{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Paket <span class="text-red-500">*</span></label>
            <input type="text" name="nama_paket" value="{{ $paketWisata->nama_paket }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="harga" value="{{ round($paketWisata->harga) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Wisata <span class="text-red-500">*</span></label>
                <input type="text" name="durasi" value="{{ $paketWisata->durasi }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Paket</label>
            <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">{{ $paketWisata->deskripsi }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Cover Paket</label>
            <div class="flex flex-col sm:flex-row items-center gap-6 mt-2 p-4 bg-gray-50 border border-gray-200 border-dashed rounded-2xl">
                
                <div class="w-40 h-28 rounded-xl overflow-hidden shrink-0 border border-gray-200 shadow-sm bg-white flex items-center justify-center">
                    <img id="preview-img" 
                         src="{{ $paketWisata->gambar ? asset('storage/' . $paketWisata->gambar) : 'https://via.placeholder.com/400x300?text=Belum+Ada+Gambar' }}" 
                         class="w-full h-full object-cover" 
                         alt="Preview">
                </div>
                
                <div class="flex-1 w-full text-center sm:text-left">
                    <h5 id="file-name" class="text-sm font-bold text-gray-800 truncate mb-1">
                        {{ $paketWisata->gambar ? 'Gambar tersimpan.' : 'Belum ada gambar.' }}
                    </h5>
                    <p class="text-xs text-gray-500 mb-3">Kosongkan jika tidak ingin mengubah gambar.</p>
                    
                    <label class="cursor-pointer inline-block bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-xs font-bold hover:bg-gray-100 hover:text-emerald-600 transition shadow-sm">
                        <span>Pilih Gambar Baru</span>
                        <input type="file" name="gambar" accept="image/*" class="sr-only" onchange="previewImage(event)">
                    </label>
                </div>

            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.paket-wisata.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Perbarui Data</button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const file = input.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('file-name').classList.add('text-emerald-600');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection