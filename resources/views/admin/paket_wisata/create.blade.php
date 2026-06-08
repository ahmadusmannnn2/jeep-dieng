@extends('admin.layouts.app')

@section('title', 'Tambah Paket Wisata - Jeep Dieng')
@section('header_title', 'Tambah Paket Wisata')
@section('header_subtitle', 'Masukkan detail paket wisata baru')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('admin.paket-wisata.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Komunitas</label>
            <select name="komunitas_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">-- Pilih Komunitas --</option>
                @foreach($komunitas as $kom)
                    <option value="{{ $kom->id }}">{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Paket <span class="text-red-500">*</span></label>
            <input type="text" name="nama_paket" required placeholder="Contoh: Paket Sunrise Sikunir" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="harga" required placeholder="Contoh: 500000" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Wisata <span class="text-red-500">*</span></label>
                <input type="text" name="durasi" required placeholder="Contoh: 4 Jam" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Paket</label>
            <textarea name="deskripsi" rows="4" placeholder="Jelaskan fasilitas dan informasi paket wisata ini..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Cover Paket (Opsional)</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden" id="drop-area">
                
                <div class="space-y-2 text-center z-10 relative" id="upload-placeholder">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    <div class="flex justify-center text-sm text-gray-600">
                        <label class="relative cursor-pointer bg-white rounded-md font-bold text-emerald-600 hover:text-emerald-500 px-3 py-1 shadow-sm border border-gray-200">
                            <span>Pilih File Gambar</span>
                            <input type="file" name="gambar" id="file-input" accept="image/*" class="sr-only" onchange="previewImage(event)">
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, WEBP maks 3MB</p>
                </div>

                <div id="image-preview-container" class="hidden absolute inset-0 z-20 bg-white flex flex-col items-center justify-center p-2">
                    <img id="preview-img" class="h-32 w-auto object-cover rounded-lg shadow-sm border border-gray-200" src="" alt="Preview">
                    <p id="file-name" class="mt-2 text-xs font-bold text-gray-700 truncate w-full text-center px-4"></p>
                    <label class="mt-2 cursor-pointer text-xs font-bold text-red-500 hover:text-red-700">
                        Ganti Gambar
                        <input type="file" name="gambar" accept="image/*" class="sr-only" onchange="previewImage(event)">
                    </label>
                </div>

            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.paket-wisata.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-3 bg-emerald-500 text-white font-medium rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Simpan Data</button>
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
                document.getElementById('upload-placeholder').classList.add('hidden');
                const previewContainer = document.getElementById('image-preview-container');
                previewContainer.classList.remove('hidden');
                
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('file-name').textContent = file.name;
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
