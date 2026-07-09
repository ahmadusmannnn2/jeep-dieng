@extends('frontend.layouts.app')

@section('title', 'Pesan ' . $paketWisata->nama_paket . ' - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))

@section('content')

@php
    $mainImage = $paketWisata->gambar ? asset('storage/' . $paketWisata->gambar) : asset('images/placeholder-landscape.svg');
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

<div x-data="{ confirmModalOpen: false }" class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Formulir Pemesanan</h1>
            <p class="text-gray-500 mt-2">Silakan lengkapi data perjalanan Anda di bawah ini.</p>
        </div>

        <form x-ref="bookingForm" action="{{ route('booking.store', $paketWisata->id) }}" method="POST" @submit.prevent="confirmModalOpen = true" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf

            <div class="lg:col-span-8 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">1</span>
                        Data Pemesan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" value="{{ Auth::user()->name }}" readonly class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" readonly class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed font-medium">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold">2</span>
                        Detail Perjalanan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Tanggal Tour --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal Tour <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" id="custom-date-picker" name="tanggal_jadwal" required placeholder="Pilih Tanggal..." class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-gray-900 bg-white cursor-pointer transition">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            @error('tanggal_jadwal')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jam Jemput --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam Jemput <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="time" name="waktu_jemput" required class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-bold text-gray-900 transition">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            @error('waktu_jemput')
                                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipe Trip --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tipe Trip <span class="text-red-500">*</span></label>
                            <select name="tipe_trip" id="tipe_trip" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-medium text-gray-800 bg-white">
                                <option value="Private">Private (1 Jeep Mandiri)</option>
                                <option value="Group">Rombongan (Multi Jeep)</option>
                            </select>
                        </div>

                        {{-- Penumpang --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jumlah Peserta (Orang) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" id="jumlah_pengunjung" name="jumlah_pengunjung" min="1" value="4" required class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-black text-emerald-600 bg-white">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1.5 font-medium">Kapasitas nyaman: 1 Jeep maksimal 4 orang.</p>
                            <div id="capacity_warning_container" class="mt-3 hidden items-start gap-2 text-xs font-semibold p-3 rounded-xl">
                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p id="capacity_warning_text"></p>
                            </div>
                        </div>

                        {{-- Lokasi Penjemputan --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Lokasi Penjemputan (Titik Kumpul) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="titik_jemput" required placeholder="Contoh: Hotel/Homestay di Dieng, Basecamp, dll" class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-medium text-gray-800 bg-white">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-3 flex items-start gap-2 text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-100 rounded-xl p-3">
                                <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p>
                                    GRATIS penjemputan di area Dieng. Di luar radius tersebut dikenakan biaya tambahan langsung ke supir mulai dari Rp 50.000.
                                </p>
                            </div>
                        </div>

                        {{-- Catatan Tambahan --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" rows="3" placeholder="Tulis catatan jika ada, misal: Bawa anak balita 1 orang, butuh 2 helm, dll." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-medium text-gray-800"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 self-start sticky top-28">
                <div class="bg-gray-900 rounded-3xl p-6 md:p-8 shadow-2xl border border-gray-800 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                    </div>

                    <h3 class="text-lg font-bold text-white border-b border-gray-700 pb-4 mb-4 relative z-10">Ringkasan Pesanan</h3>
                    <div class="flex gap-4 mb-6 relative z-10">
                        <img src="{{ $mainImage }}" alt="{{ $paketWisata->nama_paket }}" class="w-20 h-20 rounded-xl object-cover border border-gray-700 shrink-0">
                        <div>
                            <h4 class="font-bold text-white text-sm line-clamp-2 leading-snug">{{ $paketWisata->nama_paket }}</h4>
                            <p class="text-xs text-emerald-400 mt-1 font-bold">{{ $paketWisata->komunitas->nama_komunitas ?? 'Umum' }}</p>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm border-b border-gray-700 pb-6 mb-6 relative z-10">
                        <div class="flex justify-between"><span class="text-gray-400">Durasi Tour</span><span class="font-bold text-gray-200">{{ $paketWisata->durasi ?? '4 Jam' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Kebutuhan Armada</span><span id="jumlah_jeep_display" class="font-bold text-amber-400">1 Jeep</span></div>
                    </div>
                    <div class="flex flex-col items-end mb-8 relative z-10 text-right">
                        <div class="w-full flex justify-between items-end mb-1">
                            <span class="font-bold text-gray-400">Total Biaya Trip</span>
                            <span id="total_harga_display" class="text-3xl font-black text-emerald-400">Rp {{ number_format($paketWisata->harga, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-[10px] text-gray-500">*Tarif dihitung per Jeep (Rp {{ number_format($paketWisata->harga, 0, ',', '.') }} / Jeep)</span>
                    </div>

                    <button type="submit" id="btn_submit_pesanan" class="w-full py-4 bg-emerald-500 text-white text-lg font-extrabold rounded-2xl hover:bg-emerald-400 transition shadow-lg flex items-center justify-center gap-2 transform hover:-translate-y-1 relative z-10 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:hover:bg-emerald-500">
                        Buat Pesanan
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div x-show="confirmModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
        <div @click.away="confirmModalOpen = false" class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl border border-gray-100 text-center transform transition-all scale-100">
            <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 mb-2">Konfirmasi Pemesanan</h3>
            <p class="text-gray-500 text-sm mb-6">Apakah semua data perjalanan Anda sudah benar? Data yang dikirim akan langsung tercatat ke sistem.</p>
            <div class="flex gap-3">
                <button @click="confirmModalOpen = false" type="button" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Periksa Kembali</button>
                <button @click="confirmModalOpen = false; $refs.bookingForm.submit();" type="button" class="flex-1 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-md shadow-emerald-500/20">Ya, Buat Pesanan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#custom-date-picker", {
            locale: "id",
            minDate: "today",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            disableMobile: "true",
            allowInput: true,
            onReady: function(selectedDates, dateStr, instance) {
                if (instance.element.hasAttribute('required')) {
                    instance.altInput.setAttribute('required', 'required');
                    // Cegah ketikan keyboard manual agar tetap aman seperti readonly
                    instance.altInput.addEventListener('keydown', function(e) {
                        e.preventDefault();
                    });
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                // Saat tanggal berubah, cek kapasitas ke backend
                checkCapacity(dateStr);
            }
        });

        const komunitasId = {{ $paketWisata->komunitas_id ?? 'null' }};
        let currentAvailablePax = null; // null = belum dicek / unlimited as default
        let currentAvailableJeeps = null;

        const warningContainer = document.getElementById('capacity_warning_container');
        const warningText = document.getElementById('capacity_warning_text');
        const btnSubmit = document.getElementById('btn_submit_pesanan');

        function checkCapacity(dateStr) {
            if(!komunitasId || !dateStr) return;

            fetch(`{{ route('booking.check-capacity') }}?komunitas_id=${komunitasId}&tanggal_jadwal=${dateStr}`)
                .then(response => response.json())
                .then(data => {
                    if(data.error) {
                        console.error(data.error);
                        return;
                    }
                    
                    currentAvailableJeeps = data.available_jeeps;
                    currentAvailablePax = data.available_pax;
                    
                    hitungKalkulasi(); // re-evaluasi kapasitas setelah fetch
                })
                .catch(error => console.error('Error fetching capacity:', error));
        }

        // Logika Perhitungan Multi-Jeep
        const hargaPerJeep = {{ $paketWisata->harga }};
        const inputPengunjung = document.getElementById('jumlah_pengunjung');
        const inputTipeTrip = document.getElementById('tipe_trip');
        const textJumlahJeep = document.getElementById('jumlah_jeep_display');
        const textTotalHarga = document.getElementById('total_harga_display');

        function hitungKalkulasi(event) {
            let pengunjung = parseInt(inputPengunjung.value) || 1;
            
            // SINKRONISASI REALTIME TIPE TRIP:
            // Hanya jalankan auto-switch jika yang sedang diubah adalah input jumlah peserta
            if (event && event.target === inputPengunjung) {
                if (pengunjung > 4) {
                    inputTipeTrip.value = 'Group';
                } else {
                    inputTipeTrip.value = 'Private';
                }
            }

            // Hitung Jeep (Asumsi nyaman 1 Jeep = 4 orang)
            let butuhJeep = Math.ceil(pengunjung / 4);
            let totalBiaya = butuhJeep * hargaPerJeep;

            // Validasi Kapasitas
            if (currentAvailablePax !== null) {
                warningContainer.classList.remove('hidden');
                
                if (currentAvailableJeeps === 0) {
                    // Penuh total
                    warningContainer.className = 'mt-3 items-start gap-2 text-xs font-bold p-3 rounded-xl flex bg-red-50 text-red-600 border border-red-200';
                    warningText.innerText = '⚠️ Maaf, seluruh armada Jeep kami sudah habis dipesan pada tanggal ini. Silakan pilih tanggal lain.';
                    btnSubmit.disabled = true;
                    btnSubmit.innerText = 'Armada Penuh';
                } else if (butuhJeep > currentAvailableJeeps) {
                    // Sisa ada, tapi tidak cukup untuk rombongan ini
                    warningContainer.className = 'mt-3 items-start gap-2 text-xs font-bold p-3 rounded-xl flex bg-red-50 text-red-600 border border-red-200';
                    warningText.innerText = `⚠️ Sisa armada tidak mencukupi untuk rombongan Anda. Sisa armada: ${currentAvailableJeeps} Jeep (Maksimal ${currentAvailablePax} orang).`;
                    btnSubmit.disabled = true;
                    btnSubmit.innerText = 'Armada Tidak Mencukupi';
                } else {
                    // Aman
                    warningContainer.className = 'mt-3 items-start gap-2 text-xs font-bold p-3 rounded-xl flex bg-emerald-50 text-emerald-600 border border-emerald-100';
                    warningText.innerText = `✅ Tersedia! Sisa armada: ${currentAvailableJeeps} Jeep (Bisa untuk rombongan s/d ${currentAvailablePax} orang).`;
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = 'Buat Pesanan <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
                }
            } else {
                warningContainer.classList.add('hidden');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = 'Buat Pesanan <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
            }

            // Update UI
            textJumlahJeep.innerText = butuhJeep + (butuhJeep > 1 ? ' Jeeps' : ' Jeep');
            textTotalHarga.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalBiaya);
        }

        // Panggil fungsi dengan melempar 'event' agar terdeteksi siapa yang memicu
        inputPengunjung.addEventListener('input', hitungKalkulasi);
        inputTipeTrip.addEventListener('change', hitungKalkulasi);
        
        // Panggil saat awal load (tanpa event)
        hitungKalkulasi(); 
    });
</script>
@endsection
