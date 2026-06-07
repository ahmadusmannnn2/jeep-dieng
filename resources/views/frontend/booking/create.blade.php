@extends('frontend.layouts.app')

@section('title', 'Pesan ' . $paketWisata->nama_paket . ' - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))

@section('content')

@php
    $mainImage = $paketWisata->gambar ? asset('storage/' . $paketWisata->gambar) : 'https://images.unsplash.com/photo-1533692328991-08159ff19fca?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
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
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">2</span>
                        Detail Perjalanan
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal Tour <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" id="custom-date-picker" name="tanggal_jadwal" required placeholder="Pilih Tanggal..." class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 font-bold text-gray-900 bg-white cursor-pointer">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam Jemput <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="time" name="waktu_jemput" required class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 font-bold text-gray-900">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-4 bg-gray-50 border border-gray-100 rounded-2xl">
                            
                            <div class="lg:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Lokasi Penjemputan <span class="text-red-500">*</span></label>
                                <input type="text" name="titik_jemput" required placeholder="Contoh: Homestay Sikunir / Alun-alun" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition font-medium text-gray-800 bg-white">
                                
                                <div class="mt-3 flex items-start gap-2 text-xs font-medium text-gray-500">
                                    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p>
                                        GRATIS jemput di area Dieng. Di luar radius (misal pusat Wonosobo), dikenakan tambahan ongkos bensin langsung ke driver (mulai Rp 50.000).
                                    </p>
                                </div>
                            </div>

                            <div class="lg:col-span-1">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Jml Penumpang <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" name="jumlah_pengunjung" min="1" max="6" required placeholder="Maks. 6" class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition font-black text-emerald-600 bg-white">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-2 font-medium">1 armada maks. 6 orang (termasuk depan).</p>
                            </div>

                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" rows="3" placeholder="Contoh: Bawa anak balita 1 orang..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition font-medium text-gray-800"></textarea>
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
                        <div class="flex justify-between"><span class="text-gray-400">Kapasitas Maksimal</span><span class="font-bold text-gray-200">5-6 Penumpang</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Durasi Tour</span><span class="font-bold text-gray-200">{{ $paketWisata->durasi ?? '4 Jam' }}</span></div>
                    </div>
                    <div class="flex flex-col items-end mb-8 relative z-10 text-right">
                        <div class="w-full flex justify-between items-end mb-1">
                            <span class="font-bold text-gray-400">Total Biaya Trip</span>
                            <span class="text-3xl font-black text-emerald-400">Rp {{ number_format($paketWisata->harga, 0, ',', '.') }}</span>
                        </div>
                        <span class="text-[10px] text-gray-500">*Tarif flat sewa 1 kendaraan Jeep (Maks. 6 penumpang)</span>
                    </div>

                    <button type="submit" class="w-full py-4 bg-emerald-500 text-white text-lg font-extrabold rounded-2xl hover:bg-emerald-400 transition shadow-lg flex items-center justify-center gap-2 transform hover:-translate-y-1 relative z-10">
                        Buat Pesanan
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l7-7m7-7H3"></path></svg>
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
            disableMobile: "true"
        });
    });
</script>
@endsection