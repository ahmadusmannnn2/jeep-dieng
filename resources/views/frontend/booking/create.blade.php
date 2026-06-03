@extends('frontend.layouts.app')

@section('title', 'Pesan ' . $paketWisata->nama_paket . ' - Jeep Dieng')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Form Pemesanan</h2>
        <p class="text-gray-500 mt-2">Lengkapi data di bawah ini untuk memesan perjalanan Jeep Anda.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
        
        <div class="md:w-1/3 bg-gray-900 text-white p-8">
            <h3 class="text-xl font-bold text-emerald-400 mb-2">{{ $paketWisata->nama_paket }}</h3>
            <p class="text-gray-400 text-sm mb-6">{{ $paketWisata->deskripsi }}</p>
            
            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-gray-500 mb-1">Durasi</p>
                    <p class="font-semibold">{{ $paketWisata->durasi }}</p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Penyelenggara</p>
                    <p class="font-semibold">{{ $paketWisata->komunitas->nama_komunitas ?? 'Umum' }}</p>
                </div>
                <div class="pt-4 border-t border-gray-800">
                    <p class="text-gray-500 mb-1">Total Harga (Per Jeep)</p>
                    <p class="text-2xl font-bold text-emerald-400">Rp {{ number_format($paketWisata->harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="md:w-2/3 p-8">
            <form action="{{ route('booking.store', $paketWisata->id) }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Jadwal Keberangkatan <span class="text-red-500">*</span></label>
                    <select name="jadwal_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        <option value="">-- Silakan Pilih Tanggal & Jam --</option>
                        @foreach($jadwal as $item)
                            <option value="{{ $item->id }}">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }} - Jam {{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB
                            </option>
                        @endforeach
                    </select>
                    @if($jadwal->isEmpty())
                        <p class="text-red-500 text-xs mt-2">Maaf, belum ada jadwal yang tersedia untuk paket ini. Silakan hubungi admin.</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Peserta <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_pengunjung" min="1" max="6" value="4" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    <p class="text-gray-500 text-xs mt-1">*Maksimal 6 orang per Jeep (menyesuaikan kapasitas).</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" rows="3" placeholder="Misal: Penjemputan di Homestay A..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"></textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-8 py-4 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 w-full md:w-auto {{ $jadwal->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $jadwal->isEmpty() ? 'disabled' : '' }}>Konfirmasi Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection