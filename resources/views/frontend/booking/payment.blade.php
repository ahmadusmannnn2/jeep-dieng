@extends('frontend.layouts.app')

@section('title', 'Pembayaran Pesanan - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 border-b border-gray-200 pb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-emerald-500 flex items-center gap-1 mb-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dasbor
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">Selesaikan Pembayaran</h1>
                <p class="text-gray-500 mt-2">Reservasi Anda aman setelah pembayaran Uang Muka (DP) 50% diverifikasi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- KOLOM KIRI: 2 Kartu Alur Pembayaran --}}
            <div class="lg:col-span-8 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- KARTU 1: DOWN PAYMENT (DP 50%) --}}
                    @php
                        $isDpPaid = in_array($pesanan->status, ['DP Lunas', 'Lunas', 'Selesai']);
                    @endphp
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border {{ $isDpPaid ? 'border-emerald-200 bg-emerald-50/20' : 'border-gray-100' }} transition relative flex flex-col justify-between h-full">
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <span class="w-10 h-10 rounded-full {{ $isDpPaid ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} flex items-center justify-center text-base font-black">1</span>
                                @if($isDpPaid)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">✅ Terbayar</span>
                                @else
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full border border-amber-200">⏳ Belum Dibayar</span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-gray-900">Uang Muka (DP 50%)</h3>
                            <p class="text-sm text-gray-500 mt-2 mb-4">Pembayaran awal 50% untuk mengamankan unit armada Jeep & Driver pilihan Anda.</p>
                            
                            <div class="bg-white/80 backdrop-blur rounded-2xl p-4 border border-gray-100 mb-6">
                                <span class="text-xs text-gray-400 font-bold block mb-1">Nominal DP</span>
                                <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($pesanan->total_harga / 2, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div>
                            @if(!$isDpPaid)
                                @if($snapToken && $jenisPembayaran === 'DP')
                                    <button id="btn-midtrans"
                                        class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-base font-extrabold rounded-2xl hover:from-emerald-600 hover:to-teal-600 transition shadow-[0_8px_20px_rgba(16,185,129,0.35)] flex items-center justify-center gap-3 transform hover:-translate-y-1 active:translate-y-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        Bayar DP Sekarang
                                    </button>
                                @else
                                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-center text-xs text-amber-700 font-bold">
                                        ⚠️ Gagal memuat token pembayaran. Hubungi admin.
                                    </div>
                                @endif
                            @else
                                <div class="w-full py-4 bg-emerald-100 text-emerald-700 text-center font-extrabold rounded-2xl border border-emerald-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Uang Muka Selesai
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- KARTU 2: PELUNASAN (SISA 50%) --}}
                    @php
                        $isPelunasanPaid = in_array($pesanan->status, ['Lunas', 'Selesai']);
                        $isPelunasanClickable = $isDpPaid && !$isPelunasanPaid;
                    @endphp
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border transition relative flex flex-col justify-between h-full
                        {{ $isPelunasanPaid ? 'border-emerald-200 bg-emerald-50/20' : '' }}
                        {{ $isPelunasanClickable ? 'border-gray-100' : '' }}
                        {{ !$isDpPaid ? 'border-gray-100 opacity-60 bg-gray-50/50' : '' }}">
                        
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <span class="w-10 h-10 rounded-full flex items-center justify-center text-base font-black
                                    {{ $isPelunasanPaid ? 'bg-emerald-100 text-emerald-600' : '' }}
                                    {{ $isPelunasanClickable ? 'bg-amber-100 text-amber-600' : '' }}
                                    {{ !$isDpPaid ? 'bg-gray-200 text-gray-400' : '' }}">2</span>
                                
                                @if($isPelunasanPaid)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">✅ Terbayar</span>
                                @elseif($isPelunasanClickable)
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full border border-amber-200">⏳ Belum Dibayar</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-200 text-gray-500 text-xs font-bold rounded-full border border-gray-300">🔒 Terkunci</span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-gray-900">Pelunasan (Sisa 50%)</h3>
                            <p class="text-sm text-gray-500 mt-2 mb-4">Pelunasan sisa 50% tagihan Anda. Dapat dilakukan setelah pembayaran DP diverifikasi.</p>
                            
                            <div class="bg-white/80 backdrop-blur rounded-2xl p-4 border border-gray-100 mb-6">
                                <span class="text-xs text-gray-400 font-bold block mb-1">Nominal Pelunasan</span>
                                <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($pesanan->total_harga / 2, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div>
                            @if(!$isDpPaid)
                                <div class="w-full py-4 bg-gray-100 text-gray-400 text-center font-bold rounded-2xl border border-gray-200 text-xs flex items-center justify-center gap-1.5 px-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Bayar DP Terlebih Dahulu
                                </div>
                            @elseif($isPelunasanClickable)
                                @if($snapToken && $jenisPembayaran === 'Pelunasan')
                                    <button id="btn-midtrans"
                                        class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-base font-extrabold rounded-2xl hover:from-emerald-600 hover:to-teal-600 transition shadow-[0_8px_20px_rgba(16,185,129,0.35)] flex items-center justify-center gap-3 transform hover:-translate-y-1 active:translate-y-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        Bayar Pelunasan
                                    </button>
                                @else
                                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-center text-xs text-amber-700 font-bold">
                                        ⚠️ Gagal memuat token pembayaran. Hubungi admin.
                                    </div>
                                @endif
                            @else
                                <div class="w-full py-4 bg-emerald-100 text-emerald-700 text-center font-extrabold rounded-2xl border border-emerald-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Pelunasan Selesai
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- INFO NOTIFIKASI PAYMENT GATEWAY --}}
                <div class="bg-blue-50 border border-blue-100 rounded-3xl p-6 flex gap-4 items-start">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <div>
                        <p class="font-bold text-blue-900 text-sm">💡 Pembayaran Aman & Otomatis</p>
                        <p class="text-blue-700 text-xs mt-1 leading-relaxed">
                            Semua transaksi diproses secara aman menggunakan payment gateway resmi (Midtrans). Anda dapat membayar melalui transfer bank Virtual Account (BCA, Mandiri, BNI, BRI), e-wallet (GoPay, ShopeePay, OVO), QRIS, atau kartu kredit. Status pembayaran Anda akan terverifikasi secara otomatis oleh sistem dalam hitungan detik.
                        </p>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: Ringkasan Tagihan --}}
            <div class="lg:col-span-4 self-start sticky top-28">
                <div class="bg-gray-900 rounded-3xl p-6 md:p-8 shadow-2xl border border-gray-800 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>

                    <h3 class="text-lg font-bold text-white border-b border-gray-700 pb-4 mb-4 relative z-10">Ringkasan Tagihan</h3>
                    
                    <div class="mb-6 relative z-10">
                        <p class="text-xs text-gray-400 mb-1">Kode Booking</p>
                        <p class="text-lg font-bold text-emerald-400">#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>

                    <div class="space-y-3 text-sm border-b border-gray-700 pb-6 mb-6 relative z-10">
                        <div class="flex justify-between"><span class="text-gray-400">Paket Trip</span><span class="font-bold text-gray-200">{{ $pesanan->paketWisata->nama_paket ?? 'Paket Terhapus' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Tanggal</span><span class="font-bold text-gray-200">{{ \Carbon\Carbon::parse($pesanan->tanggal_jadwal)->translatedFormat('d M Y') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Jumlah Jeep</span><span class="font-bold text-gray-200">{{ $pesanan->jumlah_jeep }} unit</span></div>
                    </div>

                    <div class="relative z-10 space-y-4">
                        <div>
                            <span class="font-bold text-gray-400 block mb-1">Total Tagihan Trip</span>
                            <span class="text-3xl font-black text-white">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-800 flex justify-between items-center text-sm">
                            <span class="text-gray-400">Target Pembayaran:</span>
                            <span class="font-extrabold text-amber-400 uppercase tracking-widest text-xs px-2.5 py-1 bg-amber-500/10 rounded-lg">
                                Tahap {{ $jenisPembayaran }}
                            </span>
                        </div>

                        <div class="pt-3 flex justify-between items-center">
                            <span class="font-bold text-gray-400 text-sm">Tagihan Saat Ini (50%):</span>
                            <span class="text-2xl font-black text-emerald-400">Rp {{ number_format($jumlahBayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Midtrans Snap JS --}}
@if($snapToken && ($jenisPembayaran === 'DP' && !$isDpPaid || $jenisPembayaran === 'Pelunasan' && $isDpPaid && !$isPelunasanPaid))
@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
<script>
const snapToken  = "{{ $snapToken }}";
const finishUrl  = "{{ route('midtrans.finish') }}";
const dashboardUrl = "{{ route('dashboard') }}";

// Tombol Midtrans
const btnMidtrans = document.getElementById('btn-midtrans');
if (btnMidtrans) {
    btnMidtrans.addEventListener('click', function () {
        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                // Redirect ke halaman finish dengan order_id
                window.location.href = finishUrl + '?order_id=' + result.order_id;
            },
            onPending: function(result) {
                // Pembayaran pending
                window.location.href = dashboardUrl + '?info=pending';
            },
            onError: function(result) {
                alert('❌ Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                // User tutup popup tanpa selesai
            }
        });
    });
}
</script>
@endif
@endsection