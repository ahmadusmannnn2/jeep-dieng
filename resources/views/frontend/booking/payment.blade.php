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
                        Transfer ke Rekening Berikut
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-2xl p-5 flex items-center gap-4 hover:border-emerald-500 transition cursor-default">
                            <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center font-black text-blue-800 text-xl tracking-wider">BCA</div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase mb-0.5">A.n. Pengelola Jeep Dieng</p>
                                <p class="text-lg font-black text-gray-900 tracking-widest">8910 234 567</p>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-2xl p-5 flex items-center gap-4 hover:border-emerald-500 transition cursor-default">
                            <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center font-black text-amber-500 text-xl tracking-wider">MDR</div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase mb-0.5">A.n. Pengelola Jeep Dieng</p>
                                <p class="text-lg font-black text-gray-900 tracking-widest">1370 012 345</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-xl flex gap-3 text-amber-700 text-sm font-medium">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p>Lakukan pembayaran sesuai nominal total di sebelah kanan. Pastikan Anda menyimpan resi/bukti transfer untuk diunggah pada form di bawah.</p>
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
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Metode Pembayaran (Bank Pengirim)</label>
                                <select name="metode_pembayaran" required class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 font-bold text-gray-900 bg-white">
                                    <option value="" disabled selected>-- Pilih Bank Anda --</option>
                                    <option value="BCA">Transfer Bank BCA</option>
                                    <option value="Mandiri">Transfer Bank Mandiri</option>
                                    <option value="BRI">Transfer Bank BRI</option>
                                    <option value="BNI">Transfer Bank BNI</option>
                                    <option value="E-Wallet">E-Wallet (Gopay/Ovo/Dana)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Unggah Bukti Transfer <span class="text-red-500">*</span></label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl bg-gray-50 hover:bg-gray-100 transition relative">
                                    <div class="space-y-2 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label class="relative cursor-pointer bg-white rounded-md font-bold text-emerald-600 hover:text-emerald-500 px-2 py-0.5">
                                                <span>Pilih File Gambar</span>
                                                <input type="file" name="bukti_pembayaran" required accept="image/*" class="sr-only">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG maks. 2MB</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-emerald-500 text-white text-lg font-extrabold rounded-2xl hover:bg-emerald-600 transition shadow-[0_8px_20px_rgba(16,185,129,0.3)] flex items-center justify-center gap-2 transform hover:-translate-y-1">
                                Kirim Bukti Pembayaran
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
                        <span class="font-bold text-gray-400 block mb-1">Total yang harus dibayar</span>
                        <span class="text-4xl font-black text-emerald-400">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection