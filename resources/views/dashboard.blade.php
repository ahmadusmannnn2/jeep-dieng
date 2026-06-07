@extends('frontend.layouts.app')

@section('title', 'Riwayat Pesanan - Jeep Dieng')

@section('content')

<!-- Wrapper dibersihkan dari x-data bawaan Alpine -->
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 border-b border-gray-200 pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">Riwayat Pesanan</h1>
                <p class="text-gray-500 mt-2">Pantau status reservasi tour, tiket sobek, dan detail pembayaran Anda.</p>
            </div>
            @if(count($pesanan) > 0)
                <div class="bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3">
                    <span class="text-sm text-gray-500 font-medium">Total Pesanan:</span>
                    <span class="text-lg font-black text-gray-900">{{ count($pesanan) }}</span>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            @forelse($pesanan as $item)
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                    
                    <div class="p-6 md:px-8 md:pt-8 md:pb-6 flex flex-col lg:flex-row justify-between gap-6 lg:items-center relative">
                        
                        <div class="flex gap-5 items-start">
                            <div class="w-14 h-14 bg-gray-50 text-emerald-500 rounded-2xl flex items-center justify-center font-bold text-xl shrink-0 border border-gray-100 shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            
                            <div>
                                <div class="flex items-center gap-3 flex-wrap mb-1.5">
                                    <h4 class="font-black text-gray-900 text-xl">#BKG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</h4>
                                    
                                    @if($item->status === 'Pending')
                                        @if($item->pembayaran)
                                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-blue-200 animate-pulse">Menunggu Validasi Admin</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-amber-200">Menunggu Pembayaran</span>
                                        @endif
                                    @elseif($item->status === 'Lunas')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-emerald-200">Lunas / Valid</span>
                                    @elseif($item->status === 'Selesai')
                                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-indigo-200">Selesai Trip</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-red-50 text-red-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-red-200">Dibatalkan</span>
                                    @endif
                                </div>
                                <p class="text-base font-bold text-gray-700">{{ $item->paketWisata->nama_paket ?? 'Paket Wisata Terhapus' }}</p>
                                <p class="text-xs text-gray-400 mt-1 font-medium flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Tanggal Tour: <span class="text-gray-600 font-bold">{{ \Carbon\Carbon::parse($item->tanggal_jadwal)->translatedFormat('d F Y') }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="lg:text-right bg-gray-50 lg:bg-transparent p-4 lg:p-0 rounded-2xl lg:rounded-none border border-gray-100 lg:border-transparent">
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Total Tagihan</p>
                            <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400 mt-1">Komunitas: {{ $item->komunitas->nama_komunitas ?? 'Umum' }}</p>
                        </div>

                    </div>

                    <div class="px-6 py-4 md:px-8 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row gap-3 justify-end items-center">
                        
                        <a href="{{ route('booking.show', $item->id) }}" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition text-sm text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Lihat Detail Pembayaran
                        </a>
                        
                        @if($item->status === 'Pending' && !$item->pembayaran)
                            <a href="{{ route('booking.payment', $item->id) }}" class="w-full sm:w-auto px-6 py-2.5 bg-gray-900 text-white font-extrabold rounded-xl hover:bg-emerald-500 transition shadow-lg hover:shadow-emerald-500/30 text-sm text-center flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                Bayar Sekarang
                            </a>
                        @elseif($item->status === 'Lunas' || $item->status === 'Selesai')
                            <a href="{{ route('booking.print', $item->id) }}" target="_blank" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-500 text-white font-extrabold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 text-sm text-center flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak E-Tiket
                            </a>
                        @endif

                    </div>

                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm max-w-md mx-auto mt-10">
                    <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-5 border border-gray-100 shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-500 text-sm mb-8 leading-relaxed">Anda belum pernah melakukan pemesanan paket wisata Jeep Dieng. Yuk, rencanakan liburan pertamamu!</p>
                    <a href="{{ route('paket') }}" class="px-8 py-3.5 bg-emerald-500 text-white font-extrabold rounded-xl hover:bg-emerald-600 transition shadow-[0_8px_20px_rgba(16,185,129,0.3)] transform hover:-translate-y-1 block sm:inline-block">Cari Paket Wisata</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- PUSTAKA SWEETALERT2 UNTUK NOTIFIKASI ELEGAN  -->
<!-- ============================================== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Notifikasi Sukses Pemesanan (Awal Booking)
        @if(session('booking_success'))
            Swal.fire({
                icon: 'success',
                title: 'Booking Berhasil!',
                text: 'Pesanan Anda telah dicatat oleh sistem. Silakan lanjutkan mengunggah bukti pembayaran agar pesanan segera divalidasi oleh admin kami.',
                confirmButtonText: 'Oke, Saya Paham',
                confirmButtonColor: '#10b981',
                backdrop: `rgba(17, 24, 39, 0.8)` // Background blur gelap
            });
        @endif

        // 2. Notifikasi Sukses Mengunggah Bukti Pembayaran
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#10b981',
                backdrop: `rgba(17, 24, 39, 0.8)`
            });
        @endif

        // 3. Notifikasi Jika Ada Error/Kesalahan
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#ef4444',
                backdrop: `rgba(17, 24, 39, 0.8)`
            });
        @endif
        
    });
</script>

@endsection