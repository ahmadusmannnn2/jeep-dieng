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
            <label class="block text-sm font-medium text-gray-700 mb-2">Rute Perjalanan (Drag & Drop) <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-500 mb-3">Klik tombol tambah (+) pada rute di sebelah kiri, lalu geser (drag) kotak rute di sebelah kanan untuk mengatur urutan perjalanannya.</p>
            
            <div x-data="ruteManager()" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <!-- Rute Tersedia -->
                <div class="border border-gray-200 rounded-xl bg-gray-50 p-3 h-64 flex flex-col shadow-inner">
                    <h4 class="font-bold text-xs text-gray-700 mb-2 border-b pb-2">Rute Tersedia</h4>
                    <div class="overflow-y-auto flex-1 pr-1 space-y-1.5">
                        <template x-for="rute in ruteTersedia" :key="rute.id">
                            <div class="flex items-center justify-between px-2.5 py-1.5 bg-white border border-gray-200 rounded shadow-sm hover:border-emerald-300 transition">
                                <span class="font-medium text-xs text-gray-800" x-text="rute.nama_rute"></span>
                                <button type="button" @click="tambahRute(rute)" class="p-1 bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="ruteTersedia.length === 0" class="text-center text-[10px] font-bold text-emerald-600 mt-6">
                            Semua rute telah dimasukkan ke paket!
                        </div>
                    </div>
                </div>

                <!-- Rute Terpilih (Sortable) -->
                <div class="border border-emerald-200 rounded-xl bg-emerald-50/50 p-3 h-64 flex flex-col">
                    <h4 class="font-bold text-xs text-emerald-800 mb-2 border-b border-emerald-100 pb-2 flex justify-between items-center">
                        <span>Urutan Perjalanan</span>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-200 px-2 py-0.5 rounded-full" x-text="ruteTerpilih.length + ' Rute'"></span>
                    </h4>
                    
                    <div id="sortable-list" class="overflow-y-auto flex-1 pr-1 space-y-1.5 pb-4">
                        <template x-for="(rute, index) in ruteTerpilih" :key="rute.id">
                            <div :data-id="rute.id" class="sortable-item flex items-center gap-2 px-2.5 py-1.5 bg-white border border-emerald-300 rounded shadow-sm cursor-grab hover:shadow-md transition">
                                <div class="text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </div>
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white font-black text-[10px] flex items-center justify-center shrink-0 shadow-sm" x-text="index + 1"></div>
                                <span class="font-bold text-xs text-gray-800 flex-1" x-text="rute.nama_rute"></span>
                                <button type="button" @click="hapusRute(rute)" class="p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition" title="Keluarkan Rute">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="ruteTerpilih.length === 0" class="text-center text-xs font-medium text-emerald-600/50 mt-6">
                            ← Pilih rute dari daftar di sebelah kiri.
                        </div>
                    </div>
                </div>
                
                <!-- Input hidden untuk controller -->
                <input type="hidden" name="rute_ids" :value="ruteTerpilih.map(r => r.id).join(',')">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Cover Paket (Opsional)</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden" id="drop-area">
                
                <div class="space-y-2 text-center z-10 relative" id="upload-placeholder">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    <div class="flex justify-center text-sm text-gray-600">
                        <label for="file-input-paket" class="relative cursor-pointer bg-white rounded-md font-bold text-emerald-600 hover:text-emerald-500 px-3 py-1 shadow-sm border border-gray-200">
                            <span>Pilih File Gambar Cover</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, WEBP maks 3MB</p>
                </div>

                <div id="image-preview-container" class="hidden absolute inset-0 z-20 bg-white flex flex-col items-center justify-center p-2">
                    <img id="preview-img" class="h-32 w-auto object-cover rounded-lg shadow-sm border border-gray-200" src="" alt="Preview">
                    <p id="file-name" class="mt-2 text-xs font-bold text-gray-700 truncate w-full text-center px-4"></p>
                    <label for="file-input-paket" class="mt-2 cursor-pointer text-xs font-bold text-red-500 hover:text-red-700">
                        Ganti Gambar
                    </label>
                </div>

                <input type="file" name="gambar" id="file-input-paket" accept="image/*" class="sr-only" onchange="previewImage(event)">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Galeri Paket Wisata (Bisa Pilih Banyak Foto Sekaligus) <span class="text-xs text-gray-500 font-normal ml-2">Opsional</span></label>
            <div class="mt-1 flex flex-col p-4 border-2 border-gray-300 border-dashed rounded-2xl bg-gray-50 transition">
                <input type="file" name="galeri[]" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-gray-500 mt-2">Pilih beberapa foto sekaligus dengan menahan tombol Ctrl/Shift. Gambar ini akan tampil sebagai thumbnail galeri di bawah gambar utama paket.</p>
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

    document.addEventListener('alpine:init', () => {
        Alpine.data('ruteManager', () => ({
            ruteAsli: @json($ruteTersedia),
            ruteTersedia: [],
            ruteTerpilih: [],
            
            init() {
                this.ruteTersedia = [...this.ruteAsli];
                
                // Init SortableJS setelah DOM siap
                this.$nextTick(() => {
                    const el = document.getElementById('sortable-list');
                    if (el) {
                        Sortable.create(el, {
                            animation: 150,
                            ghostClass: 'opacity-50',
                            onEnd: (evt) => {
                                // Pindahkan item di dalam array saat drag selesai
                                const item = this.ruteTerpilih.splice(evt.oldIndex, 1)[0];
                                this.ruteTerpilih.splice(evt.newIndex, 0, item);
                            }
                        });
                    }
                });
            },
            
            tambahRute(rute) {
                this.ruteTerpilih.push(rute);
                this.ruteTersedia = this.ruteTersedia.filter(r => r.id !== rute.id);
            },
            
            hapusRute(rute) {
                this.ruteTersedia.push(rute);
                this.ruteTerpilih = this.ruteTerpilih.filter(r => r.id !== rute.id);
                // Urutkan kembali daftar yang tersedia berdasarkan abjad
                this.ruteTersedia.sort((a, b) => a.nama_rute.localeCompare(b.nama_rute));
            }
        }));
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endsection
