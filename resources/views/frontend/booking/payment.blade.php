@extends('frontend.layouts.app')

@section('title', 'Pembayaran Pesanan - ' . ($pengaturan_website->nama_website ?? 'Jeep Dieng'))

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selesaikan Pembayaran</h1>
            <p class="text-gray-500 mt-2">Pesanan Anda berhasil dibuat. Selesaikan pembayaran agar Jeep Anda segera disiapkan.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-8 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">1</span>
                        Metode Pembayaran
                    </h3>
                    <div class="p-5 border border-emerald-200 bg-emerald-50 rounded-2xl flex items-center gap-4">
                        <div class="w-16 h-12 bg-white rounded-lg flex items-center justify-center font-black text-emerald-600 text-sm tracking-wider shadow-sm border border-emerald-100">PAY</div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase mb-0.5">Sistem Pembayaran Otomatis</p>
                            <p class="text-sm font-medium text-gray-800">Pembayaran akan diproses secara aman melalui *Payment Gateway*. Anda dapat memilih berbagai metode seperti Transfer Bank (Virtual Account), E-Wallet, atau Kartu Kredit di halaman selanjutnya.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">2</span>
                        Konfirmasi Pembayaran
                    </h3>

                    <form action="{{ route('booking.payment.store', $pesanan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-6">
                            @if($pesanan->status === 'DP Lunas')
                                <input type="hidden" name="jenis_pembayaran" value="Pelunasan">
                                <div class="p-4 bg-blue-50 border border-blue-100 text-blue-700 rounded-xl">
                                    <p class="font-bold text-sm mb-1">Pembayaran Pelunasan</p>
                                    <p class="text-xs">Sisa tagihan yang harus dibayar: Rp {{ number_format($pesanan->total_harga / 2, 0, ',', '.') }}</p>
                                </div>
                            @else
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Jenis Pembayaran <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="jenis_pembayaran" value="Lunas" checked class="peer sr-only">
                                            <div class="p-4 rounded-xl border border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition text-center hover:bg-gray-50 h-full flex flex-col justify-center">
                                                <p class="font-bold text-gray-900">Bayar Penuh</p>
                                                <p class="text-xs text-gray-500 mt-1 mb-2">Langsung lunas 100%</p>
                                                <p class="text-lg font-black text-emerald-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="jenis_pembayaran" value="DP" class="peer sr-only">
                                            <div class="p-4 rounded-xl border border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition text-center hover:bg-gray-50 h-full flex flex-col justify-center">
                                                <p class="font-bold text-gray-900">DP 50%</p>
                                                <p class="text-xs text-gray-500 mt-1 mb-2">Amankan jadwal Jeep</p>
                                                <p class="text-lg font-black text-emerald-600">Rp {{ number_format($pesanan->total_harga / 2, 0, ',', '.') }}</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <button type="button" class="w-full py-4 bg-emerald-500 text-white text-lg font-extrabold rounded-2xl hover:bg-emerald-600 transition shadow-[0_8px_20px_rgba(16,185,129,0.3)] flex items-center justify-center gap-2 transform hover:-translate-y-1">
                                Lanjutkan ke Pembayaran (Midtrans)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

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
                        <div class="flex justify-between"><span class="text-gray-400">Jam Jemput</span><span class="font-bold text-gray-200">Menyesuaikan</span></div>
                    </div>

                    <div class="relative z-10">
                        <span class="font-bold text-gray-400 block mb-1">Total Tagihan Pesanan</span>
                        <span class="text-4xl font-black text-emerald-400">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        <p class="text-xs text-gray-400 mt-2 font-medium">*Tarif sewa flat untuk 1 kendaraan Jeep (Kapasitas maks. 6 penumpang)</p>
                        
                        @if($pesanan->status === 'DP Lunas')
                        <div class="mt-5 pt-5 border-t border-gray-700">
                            <span class="font-bold text-gray-400 block mb-1">Sisa yang harus dilunasi</span>
                            <span class="text-3xl font-black text-amber-400">Rp {{ number_format($pesanan->total_harga / 2, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection