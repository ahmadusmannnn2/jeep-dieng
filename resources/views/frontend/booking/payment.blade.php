@extends('frontend.layouts.app')

@section('title', 'Pembayaran #BKG-' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Konfirmasi Pembayaran</h2>
        <p class="text-gray-500 mt-2">Pesanan Anda telah disetujui admin. Silakan selesaikan pembayaran untuk mengamankan jadwal Anda.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 bg-gray-900 text-white">
            <p class="text-gray-400 text-sm mb-1">Total Tagihan (Kode Booking: #BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }})</p>
            <h3 class="text-4xl font-black text-emerald-400 mb-4">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h3>
            
            <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
                <p class="font-bold mb-2 text-emerald-400">Instruksi Pembayaran:</p>
                <p class="text-gray-300 text-sm mb-3">Silakan transfer sesuai nominal di atas ke salah satu rekening resmi kami:</p>
                <ul class="list-disc pl-5 text-sm text-gray-300 space-y-2">
                    <li><span class="font-bold">BCA:</span> 1234567890 a.n. Jeep Dieng Official</li>
                    <li><span class="font-bold">BRI:</span> 0987654321 a.n. Jeep Dieng Official</li>
                    <li><span class="font-bold">Dana/GoPay:</span> 081234567890</li>
                </ul>
            </div>
        </div>

        <div class="p-8">
            <form action="{{ route('booking.payment.store', $pesanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Bank/Metode Transfer Anda <span class="text-red-500">*</span></label>
                    <select name="metode_pembayaran" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer BCA">Transfer BCA</option>
                        <option value="Transfer BRI">Transfer BRI</option>
                        <option value="E-Wallet (Dana/GoPay/Ovo)">E-Wallet (Dana/GoPay/Ovo)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                    <input type="file" name="bukti_bayar" accept="image/*" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-gray-500 mt-2">*Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.</p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-4">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold text-center rounded-xl hover:bg-gray-200 transition">Nanti Saja</a>
                    <button type="submit" class="px-8 py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Kirim Bukti Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection