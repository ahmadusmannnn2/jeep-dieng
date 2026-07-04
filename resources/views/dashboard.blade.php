@extends('frontend.layouts.app')

@section('title', 'Riwayat Pesanan - Jeep Dieng')

@section('content')

<!-- Wrapper dibersihkan dari x-data bawaan Alpine -->
<div x-data="{ showReviewModal: false, reviewFormAction: '', rating: 5, hoveredRating: 0 }" class="py-12 bg-gray-50 min-h-screen">
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
                                    @elseif($item->status === 'DP Lunas')
                                        <span class="px-2.5 py-1 bg-teal-50 text-teal-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-teal-200">DP Lunas (50%)</span>
                                    @elseif($item->status === 'Selesai Perjalanan')
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-blue-200">Selesai Perjalanan (Tunggu Pelunasan)</span>
                                    @elseif($item->status === 'Lunas')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-emerald-200">Lunas</span>
                                    @elseif($item->status === 'Selesai')
                                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-indigo-200">Selesai</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-red-50 text-red-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-red-200">Batal</span>
                                    @endif
                                </div>
                                <p class="text-base font-bold text-gray-700">{{ $item->paketWisata->nama_paket ?? 'Paket Wisata Terhapus' }}</p>
                                <p class="text-xs text-gray-400 mt-1 font-medium flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Tanggal Tour: <span class="text-gray-600 font-bold">{{ \Carbon\Carbon::parse($item->tanggal_jadwal)->translatedFormat('d F Y') }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="lg:text-right bg-gray-50 lg:bg-transparent p-4 lg:p-0 rounded-2xl lg:rounded-none border border-gray-100 lg:border-transparent min-w-[200px]">
                            @php
                                $totalDibayar = $item->pembayarans ? $item->pembayarans->where('status', 'Valid')->sum('jumlah_bayar') : 0;
                                $sisaTagihan = max(0, $item->total_harga - $totalDibayar);
                            @endphp
                            
                            <div class="flex justify-between lg:justify-end gap-6 text-sm mb-1">
                                <span class="text-gray-500 font-medium">Total Harga:</span>
                                <span class="font-bold text-gray-700">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between lg:justify-end gap-6 text-sm mb-2 border-b border-gray-200 pb-2">
                                <span class="text-emerald-600 font-medium">Telah Dibayar:</span>
                                <span class="font-bold text-emerald-600">- Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between lg:justify-end gap-6">
                                <span class="text-xs text-gray-500 uppercase font-bold mt-1">Sisa Tagihan:</span>
                                <span class="text-2xl font-black {{ $sisaTagihan > 0 ? 'text-red-500' : 'text-emerald-500' }}">
                                    Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class="px-6 py-4 md:px-8 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row gap-3 justify-end items-center">
                        
                        <a href="{{ route('booking.show', $item->id) }}" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition text-sm text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Lihat Detail Pembayaran
                        </a>
                        
                        @php
                            $hasValidPayment = $item->pembayaran && $item->pembayaran->status === 'Valid';
                        @endphp
                        @if(($item->status === 'Pending' && !$hasValidPayment) || $item->status === 'Selesai Perjalanan')
                            <a href="{{ route('booking.payment', $item->id) }}" class="w-full sm:w-auto px-6 py-2.5 bg-gray-900 text-white font-extrabold rounded-xl hover:bg-emerald-500 transition shadow-lg hover:shadow-emerald-500/30 text-sm text-center flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                {{ $item->status === 'Selesai Perjalanan' ? 'Bayar Pelunasan' : 'Bayar Sekarang' }}
                            </a>
                        @elseif($item->status === 'DP Lunas')
                            <div class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 text-gray-500 font-bold rounded-xl text-sm text-center flex items-center justify-center gap-2 border border-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Menunggu Perjalanan Selesai
                            </div>
                        @elseif($item->status === 'Lunas' || $item->status === 'Selesai')
                            <a href="{{ route('booking.print', $item->id) }}" target="_blank" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-500 text-white font-extrabold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 text-sm text-center flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak E-Tiket
                            </a>
                            @if(!$item->testimoni)
                                <button type="button" @click="showReviewModal = true; reviewFormAction = '{{ route('booking.testimoni', $item->id) }}'" class="w-full sm:w-auto px-6 py-2.5 bg-yellow-400 text-yellow-900 font-extrabold rounded-xl hover:bg-yellow-500 transition shadow-lg shadow-yellow-400/30 text-sm text-center flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    Beri Ulasan
                                </button>
                            @else
                                <div class="w-full sm:w-auto px-6 py-2.5 bg-green-50 text-green-600 font-bold rounded-xl text-sm text-center flex items-center justify-center gap-2 border border-green-200 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    Sudah Diulas
                                </div>
                            @endif
                        @endif

                        @if(in_array($item->status, ['Pending', 'Dibatalkan']))
                            <form action="{{ route('booking.destroy', $item->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus pesanan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-red-50 text-red-600 font-extrabold rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm text-sm text-center flex items-center justify-center gap-2 transform hover:-translate-y-0.5 border border-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Batal & Hapus
                                </button>
                            </form>
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

    <!-- MODAL REVIEW (ALPINE.JS) -->
    <div x-show="showReviewModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showReviewModal = false"></div>

        <!-- Modal Content -->
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-xl font-extrabold text-gray-900">Beri Ulasan Perjalanan</h3>
                <button type="button" @click="showReviewModal = false" class="text-gray-400 hover:text-red-500 transition-colors p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="reviewFormAction" method="POST" class="p-6 md:p-8">
                @csrf

                <!-- Interactive Star Rating -->
                <div class="mb-6 text-center">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Berapa Bintang Untuk Kami?</p>
                    <div class="flex justify-center gap-2">
                        <template x-for="i in 5">
                            <button type="button" 
                                @mouseenter="hoveredRating = i" 
                                @mouseleave="hoveredRating = 0" 
                                @click="rating = i" 
                                class="focus:outline-none transition-transform hover:scale-110">
                                <svg class="w-10 h-10 transition-colors duration-200" :class="(hoveredRating ? i <= hoveredRating : i <= rating) ? 'text-yellow-400 drop-shadow-md' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" :value="rating">
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Asal Kota <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="asal_kota" placeholder="Contoh: Jakarta" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition bg-gray-50 focus:bg-white text-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Kesan & Pesan <span class="text-red-500">*</span></label>
                        <textarea name="pesan" rows="4" required placeholder="Ceritakan pengalaman luar biasa Anda selama tour..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition bg-gray-50 focus:bg-white text-gray-900 resize-none"></textarea>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full py-3.5 bg-emerald-500 text-white font-extrabold rounded-xl hover:bg-emerald-600 transition shadow-[0_8px_20px_rgba(16,185,129,0.3)] transform hover:-translate-y-0.5">
                        Kirim Ulasan Sekarang
                    </button>
                </div>
            </form>
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