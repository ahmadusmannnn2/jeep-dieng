@extends('frontend.layouts.app')

@section('title', 'Riwayat Pesanan - Jeep Dieng')

@section('content')

<div x-data="{ successModalOpen: {{ session('booking_success') ? 'true' : 'false' }} }" class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 border-b border-gray-200 pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">Riwayat Pesanan</h1>
                <p class="text-gray-500 mt-2">Pantau status reservasi tour, tiket sobek, dan upload bukti pembayaran Anda.</p>
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
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-amber-200">Menunggu Pembayaran</span>
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
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Total Pembayaran</p>
                            <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400 mt-1">Komunitas: {{ $item->komunitas->nama_komunitas ?? 'Umum' }}</p>
                        </div>

                    </div>

                    <div class="px-6 py-4 md:px-8 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row gap-3 justify-end items-center">
                        
                        <a href="{{ route('paket.show', $item->paket_wisata_id) }}" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-100 transition text-sm text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Detail Paket
                        </a>
                        
                        @if($item->status === 'Pending')
                            <a href="{{ route('booking.payment', $item->id) }}" class="w-full sm:w-auto px-6 py-2.5 bg-gray-900 text-white font-extrabold rounded-xl hover:bg-emerald-500 transition shadow-lg hover:shadow-emerald-500/30 text-sm text-center flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                Bayar Sekarang
                            </a>
                        @else
                            <a href="{{ route('booking.show', $item->id) }}" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-500 text-white font-extrabold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 text-sm text-center flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                Detail E-Tiket
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

    <div x-show="successModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
        <div @click.away="successModalOpen = false" 
             x-show="successModalOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4"
             class="bg-white rounded-[32px] p-8 max-w-sm w-full shadow-2xl border border-gray-100 text-center relative overflow-hidden">
            
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-xl"></div>
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-teal-500/5 rounded-full blur-xl"></div>

            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-emerald-100 transform scale-100 animate-bounce duration-1000">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            
            <h3 class="text-2xl font-black text-gray-900 mb-2">Booking Berhasil!</h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-8 px-2">
                Pesanan Anda telah dicatat oleh sistem. Silakan lanjutkan mengunggah bukti pembayaran agar pesanan segera divalidasi oleh admin kami.
            </p>
            
            <button @click="successModalOpen = false" type="button" class="w-full py-3.5 bg-gray-900 text-white font-extrabold rounded-2xl hover:bg-emerald-500 transition shadow-lg hover:shadow-emerald-500/20 transform active:scale-95">
                Oke, Saya Paham
            </button>
        </div>
    </div>
</div>
@endsection