@extends('admin.layouts.app')

@section('title', 'Edit Paket Wisata - Jeep Dieng')
@section('header_title', 'Edit Paket Wisata')
@section('header_subtitle', 'Perbarui detail paket wisata')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('admin.paket-wisata.update', $paketWisata->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Komunitas</label>
            <select name="komunitas_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">-- Pilih Komunitas --</option>
                @foreach($komunitas as $kom)
                    <option value="{{ $kom->id }}" {{ $paketWisata->komunitas_id == $kom->id ? 'selected' : '' }}>{{ $kom->nama_komunitas }}</option>
                @endforeach
            </select>
        </div>

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
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Tempat</label>
            <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">{{ $paketWisata->deskripsi }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rute Perjalanan (Drag & Drop) <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-500 mb-3">Klik tombol tambah (+) pada rute di sebelah kiri, lalu geser (drag) kotak rute di sebelah kanan untuk mengatur urutan perjalanannya.</p>
            
            <div x-data="ruteManager()" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Rute Tersedia -->
                <div class="border border-gray-200 rounded-xl bg-gray-50 p-4 h-80 flex flex-col shadow-inner">
                    <h4 class="font-bold text-sm text-gray-700 mb-3 border-b pb-2">Rute Tersedia</h4>
                    <div class="overflow-y-auto flex-1 pr-2 space-y-2">
                        <template x-for="rute in ruteTersedia" :key="rute.id">
                            <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-emerald-300 transition">
                                <span class="font-medium text-sm text-gray-800" x-text="rute.nama_rute"></span>
                                <button type="button" @click="tambahRute(rute)" class="p-1.5 bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="ruteTersedia.length === 0" class="text-center text-xs font-bold text-emerald-600 mt-10">
                            Semua rute telah dimasukkan ke paket!
                        </div>
                    </div>
                </div>

                <!-- Rute Terpilih (Sortable) -->
                <div class="border border-emerald-200 rounded-xl bg-emerald-50/50 p-4 h-80 flex flex-col">
                    <h4 class="font-bold text-sm text-emerald-800 mb-3 border-b border-emerald-100 pb-2 flex justify-between items-center">
                        <span>Urutan Perjalanan</span>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-200 px-2.5 py-0.5 rounded-full" x-text="ruteTerpilih.length + ' Rute'"></span>
                    </h4>
                    
                    <div id="sortable-list" class="overflow-y-auto flex-1 pr-2 space-y-2 pb-10">
                        <template x-for="(rute, index) in ruteTerpilih" :key="rute.id">
                            <div :data-id="rute.id" class="sortable-item flex items-center gap-3 p-3 bg-white border border-emerald-300 rounded-lg shadow-sm cursor-grab hover:shadow-md transition">
                                <div class="text-emerald-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </div>
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm" x-text="index + 1"></div>
                                <span class="font-bold text-sm text-gray-800 flex-1" x-text="rute.nama_rute"></span>
                                <button type="button" @click="hapusRute(rute)" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition" title="Keluarkan Rute">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="ruteTerpilih.length === 0" class="text-center text-sm font-medium text-emerald-600/50 mt-10">
                            ← Pilih rute dari daftar di sebelah kiri.
                        </div>
                    </div>
                </div>
                
                <!-- Input hidden untuk controller -->
                <input type="hidden" name="rute_ids" :value="ruteTerpilih.map(r => r.id).join(',')">
            </div>
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
                    
                    <label for="file-input-edit-paket" class="cursor-pointer inline-block bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-xs font-bold hover:bg-gray-100 hover:text-emerald-600 transition shadow-sm">
                        <span>Pilih Gambar Baru</span>
                    </label>
                    <input type="file" name="gambar" id="file-input-edit-paket" accept="image/*" class="sr-only" onchange="previewImage(event)">
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

    document.addEventListener('alpine:init', () => {
        Alpine.data('ruteManager', () => ({
            ruteAsli: @json($ruteTersedia),
            ruteTerpilihAwal: @json($paketWisata->rutes),
            ruteTersedia: [],
            ruteTerpilih: [],
            
            init() {
                // Set rute terpilih awal
                this.ruteTerpilih = [...this.ruteTerpilihAwal];
                
                // Set rute tersedia (buang yang sudah terpilih)
                const terpilihIds = this.ruteTerpilih.map(r => r.id);
                this.ruteTersedia = this.ruteAsli.filter(r => !terpilihIds.includes(r.id));
                
                // Init SortableJS setelah DOM siap
                this.$nextTick(() => {
                    const el = document.getElementById('sortable-list');
                    if (el) {
                        Sortable.create(el, {
                            animation: 150,
                            ghostClass: 'opacity-50',
                            onEnd: (evt) => {
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
                this.ruteTersedia.sort((a, b) => a.nama_rute.localeCompare(b.nama_rute));
            }
        }));
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endsection
