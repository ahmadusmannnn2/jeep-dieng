@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #BKG-' . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT))
@section('header_title', 'Kelola Pesanan Masuk')
@section('header_subtitle', 'Validasi pembayaran dan tugaskan armada Jeep')

@section('content')

@if($pesanan->status !== 'Dibatalkan')
    @php
        $step1 = true;
        $step2 = in_array($pesanan->status, ['DP Lunas', 'Selesai Perjalanan', 'Lunas', 'Selesai']);
        $step3 = in_array($pesanan->status, ['Selesai Perjalanan', 'Lunas', 'Selesai']);
        $step4 = in_array($pesanan->status, ['Lunas', 'Selesai']);
        $step5 = $pesanan->testimoni !== null;
        
        $steps = [
            ['label' => 'Booking Dibuat', 'active' => $step1],
            ['label' => 'DP Terbayar', 'active' => $step2],
            ['label' => 'Trip Selesai', 'active' => $step3],
            ['label' => 'Lunas Penuh', 'active' => $step4],
            ['label' => 'Diulas (Selesai)', 'active' => $step5],
        ];
    @endphp
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 hidden md:block">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest text-center mb-6">Indikator Progres Pesanan</h3>
        <div class="relative max-w-4xl mx-auto">
            <!-- Connecting Line Background -->
            <div class="absolute left-[10%] right-[10%] top-3 -translate-y-1/2 h-1 bg-gray-100 rounded-full z-0"></div>
            <!-- Connecting Line Active -->
            <div class="absolute left-[10%] top-3 -translate-y-1/2 h-1 bg-emerald-500 rounded-full z-0 transition-all duration-1000 ease-in-out" 
                 style="width: {{ $step5 ? '80%' : ($step4 ? '60%' : ($step3 ? '40%' : ($step2 ? '20%' : '0%'))) }};"></div>
            
            <div class="flex justify-between relative z-10">
                @foreach($steps as $idx => $step)
                    <div class="flex flex-col items-center gap-2 w-1/5">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 transition-colors duration-500 {{ $step['active'] ? 'bg-emerald-500 border-emerald-500 text-white shadow-[0_0_10px_rgba(16,185,129,0.4)]' : 'bg-white border-gray-200 text-transparent' }}">
                            <svg class="w-3 h-3 {{ $step['active'] ? 'block animate-fade-in' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-center {{ $step['active'] ? 'text-emerald-600' : 'text-gray-400' }}">{{ $step['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="flex justify-between items-start border-b border-gray-100 pb-4 mb-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h3 class="text-xl font-black text-gray-900">#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-700 text-[10px] font-bold rounded-lg uppercase tracking-wider">{{ $pesanan->tipe_trip ?? 'Private' }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Dibuat pada: {{ $pesanan->created_at->translatedFormat('d M Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="block text-xs uppercase text-gray-400 font-bold mb-1">Status Pembayaran</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border 
                        {{ $pesanan->status === 'Lunas' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                        {{ $pesanan->status === 'DP Lunas' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                        {{ $pesanan->status === 'Pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                        {{ $pesanan->status === 'Selesai' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                        {{ $pesanan->status === 'Dibatalkan' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                        @if($pesanan->status === 'Pending')
                            Menunggu Pembayaran
                        @elseif($pesanan->status === 'Dibatalkan')
                            Batal
                        @else
                            {{ $pesanan->status }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Pelanggan</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->user->name ?? '-' }}</p>
                    <p class="text-gray-500">{{ $pesanan->user->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Paket Wisata</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->paketWisata->nama_paket ?? '-' }}</p>
                    <p class="text-gray-500">{{ $pesanan->komunitas->nama_komunitas ?? 'Umum' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Jadwal Tour</p>
                    <p class="font-bold text-emerald-600">{{ \Carbon\Carbon::parse($pesanan->tanggal_jadwal)->translatedFormat('l, d F Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase mb-1">Kapasitas</p>
                    <p class="font-bold text-gray-800">{{ $pesanan->jumlah_pengunjung }} Orang ({{ $pesanan->jumlah_jeep ?? 1 }} Jeep)</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <p class="text-gray-400 text-xs font-bold uppercase mb-1">Titik Kumpul / Lokasi Jemput</p>
                <p class="font-bold text-gray-800">{{ $pesanan->titik_jemput }}</p>
            </div>
            
            @if($pesanan->catatan)
            <div class="mt-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Catatan Pesanan & Waktu:</p>
                <p class="text-sm font-medium text-gray-800 whitespace-pre-line">{{ $pesanan->catatan }}</p>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4 mb-4">Daftar Penugasan Armada</h3>
            
            <div class="space-y-4">
                @forelse($pesanan->armadas as $index => $armada)
                    <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-2xl bg-gray-50">
                        <div class="w-10 h-10 bg-gray-900 text-white rounded-xl flex items-center justify-center font-black">
                            #{{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $armada->jeep->nama_jeep ?? 'Jeep Belum Dipilih' }} <span class="text-sm font-normal text-gray-500">({{ $armada->jeep->nomor_polisi ?? '-' }})</span></p>
                            <p class="text-sm text-gray-600">Supir: <span class="font-medium">{{ $armada->supir->nama_supir ?? 'Belum Ditugaskan' }}</span></p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 bg-amber-50 rounded-2xl border border-dashed border-amber-200">
                        <p class="text-amber-700 font-medium text-sm">Belum ada armada yang ditugaskan untuk pesanan ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4 mb-4">Histori Pembayaran</h3>
            
            @if($pesanan->pembayarans && $pesanan->pembayarans->count() > 0)
                <div class="space-y-8">
                @foreach($pesanan->pembayarans as $index => $bayar)
                    <div class="flex flex-col md:flex-row gap-6 relative">
                        @if($index > 0)
                            <div class="absolute -top-6 left-0 right-0 border-t border-dashed border-gray-200"></div>
                        @endif
                        <div class="w-full md:w-1/2">
                            @if($bayar->bukti_bayar)
                                <a href="{{ asset('storage/' . $bayar->bukti_bayar) }}" target="_blank" class="block border-2 border-gray-200 rounded-2xl overflow-hidden hover:border-emerald-500 transition relative group shadow-sm">
                                    <img src="{{ asset('storage/' . $bayar->bukti_bayar) }}" alt="Bukti Bayar" class="w-full h-auto max-h-64 object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">Klik untuk Perbesar</span>
                                    </div>
                                </a>
                            @else
                                <div class="bg-gradient-to-br from-gray-900 to-slate-800 rounded-2xl p-6 text-white shadow-md relative overflow-hidden h-full flex flex-col justify-between">
                                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
                                    <div>
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-[10px] font-black uppercase tracking-widest bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded">MIDTRANS</span>
                                            <span class="text-xs text-gray-400 font-medium">Auto-Payment Gateway</span>
                                        </div>
                                        <p class="text-xs text-gray-400 font-bold uppercase">Order ID</p>
                                        <p class="font-mono text-sm font-semibold text-emerald-400 mb-3">{{ $bayar->midtrans_order_id ?? '-' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 border-t border-gray-700/50 pt-3 text-xs text-gray-300">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        <span>Terverifikasi Sistem</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="w-full md:w-1/2 space-y-3.5 text-sm">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full uppercase tracking-wider">{{ $bayar->jenis_pembayaran }}</span>
                                <span class="text-gray-500 text-xs">{{ $bayar->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-bold uppercase">Metode / Saluran</p>
                                <p class="font-black text-gray-900 text-lg">
                                    {{ $bayar->metode_pembayaran }} 
                                    @if($bayar->payment_channel)
                                        <span class="text-sm font-medium text-gray-500">({{ strtoupper($bayar->payment_channel) }})</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-bold uppercase font-bold">Status Verifikasi</p>
                                <span class="inline-block px-2.5 py-0.5 text-xs font-bold rounded-md
                                    {{ $bayar->status === 'Valid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                    {{ $bayar->status }}
                                </span>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-bold uppercase">Nominal Dibayar</p>
                                <p class="font-black text-emerald-600 text-2xl">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            @else
                <div class="text-center py-8 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-500 font-medium">Belum ada riwayat pembayaran yang tercatat.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-1 items-start space-y-6">
        @if(Auth::user()->role === 'pengelola' && $pesanan->status === 'DP Lunas')
            @if($pesanan->armadas()->count() > 0)
                <form action="{{ route('admin.pesanan.selesai-perjalanan', $pesanan->id) }}" method="POST" class="bg-blue-50 border border-blue-200 rounded-3xl shadow-sm p-6 relative overflow-hidden mb-6" onsubmit="return confirm('Yakin ingin menandai perjalanan ini telah selesai? Customer akan segera ditagih untuk melunasi sisa pembayaran.');">
                    @csrf
                    @method('PATCH')
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Konfirmasi Selesai Trip</h3>
                        <p class="text-sm text-blue-700 mb-4">Tekan tombol di bawah jika aktivitas trip Jeep Dieng sudah sepenuhnya selesai dilaksanakan oleh armada Anda.</p>
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-extrabold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Tandai Selesai Perjalanan
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-3xl shadow-sm p-6 relative overflow-hidden mb-6">
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-amber-900 mb-2">Tugaskan Armada Terlebih Dahulu</h3>
                        <p class="text-sm text-amber-800">Tiket ini sudah "DP Lunas", namun Anda belum menugaskan plat nomor Jeep/Armada. Silakan pilih Jeep pada kotak form di bawah ini agar tombol konfirmasi perjalanan dapat terbuka.</p>
                    </div>
                </div>
            @endif
        @endif

        @if(Auth::user()->role === 'admin')
        <!-- ======================= -->
        <!-- FORMULIR KHUSUS ADMIN -->
        <!-- ======================= -->
        <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST" class="bg-gray-900 rounded-3xl shadow-xl border border-gray-800 p-6 md:p-8 sticky top-24 z-10">
            @csrf
            @method('PUT')

            <h3 class="text-lg font-bold text-white border-b border-gray-700 pb-4 mb-6">Penetapan Status & Komunitas</h3>

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">1. Pilih Komunitas Pengelola</label>
                <select name="komunitas_id" class="w-full px-4 py-3 rounded-xl border border-gray-700 bg-gray-800 text-white font-bold focus:ring-2 focus:ring-emerald-500 transition">
                    <option value="">-- Belum Ditentukan --</option>
                    @foreach($semuaKomunitas as $kom)
                        <option value="{{ $kom->id }}" {{ $pesanan->komunitas_id == $kom->id ? 'selected' : '' }}>
                            {{ $kom->nama_komunitas }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-500 mt-2">Komunitas terpilih akan mendapat Lonceng Penugasan Jeep dari sistem.</p>
            </div>

            <div class="mb-6">
                <div class="p-4 rounded-xl border border-emerald-500/20 bg-emerald-500/5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status Sistem (Auto-Sync)</p>
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <p class="text-emerald-400 font-black text-lg uppercase tracking-widest">{{ $pesanan->status }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-emerald-500 text-white text-base font-extrabold rounded-xl hover:bg-emerald-400 transition shadow-[0_4px_15px_rgba(16,185,129,0.3)] mb-4">
                Simpan Status & Komunitas
            </button>
            
            <p class="text-[10px] text-gray-400 text-center mb-6 border-b border-gray-700 pb-6">Catatan: Admin tidak lagi menugaskan Jeep. Penugasan akan dilakukan mandiri oleh Pengelola terkait.</p>

            @php
                $pengelolaUser = \App\Models\User::where('role', 'pengelola')
                                    ->where('komunitas_id', $pesanan->komunitas_id)
                                    ->whereNotNull('no_hp')
                                    ->first();
                $noHpPengelola = $pengelolaUser ? $pengelolaUser->no_hp : '';
                
                if($noHpPengelola && str_starts_with($noHpPengelola, '0')) {
                    $noHpPengelola = '62' . substr($noHpPengelola, 1);
                }

                $waText = "Halo Bapak/Ibu Pengelola Komunitas " . ($pesanan->komunitas->nama_komunitas ?? 'Jeep Dieng') . ".\n\n" .
                          "*Berikut Penugasan Armada Baru:*\n" .
                          "Kode Booking: #BKG-" . str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) . "\n" .
                          "Tgl Tour: " . \Carbon\Carbon::parse($pesanan->tanggal_jadwal)->translatedFormat('d M Y') . "\n" .
                          "Paket: " . ($pesanan->paketWisata->nama_paket ?? '-') . "\n" .
                          "Titik Jemput: " . $pesanan->titik_jemput . "\n" .
                          "Total Peserta: " . $pesanan->jumlah_pengunjung . " Orang\n" .
                          "Jumlah Armada: " . ($pesanan->jumlah_jeep ?? 1) . " Jeep\n" .
                          "Catatan: " . ($pesanan->catatan ?? '-') . "\n\n" .
                          "Dimohon kerjasamanya untuk segera mengatur jadwal armada ini. Silakan cek dasbor Pengelola untuk mencetak Surat Jalan. Terima kasih!";
                
                $waLink = $noHpPengelola 
                            ? "https://api.whatsapp.com/send?phone={$noHpPengelola}&text=" . urlencode($waText)
                            : "https://api.whatsapp.com/send?text=" . urlencode($waText);
            @endphp
            
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Instruksi Lapangan</label>
            <a href="{{ $waLink }}" target="_blank" class="w-full py-3.5 bg-[#25D366] text-white text-sm font-extrabold rounded-xl hover:bg-[#128C7E] transition shadow-md flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                Teruskan Penugasan ke WA Pengelola {{ $pesanan->komunitas->nama_komunitas ?? '' }}
            </a>
        </form>

        @elseif(Auth::user()->role === 'pengelola')
        <!-- ========================== -->
        <!-- FORMULIR KHUSUS PENGELOLA  -->
        <!-- ========================== -->
        <div class="bg-gray-900 rounded-3xl shadow-xl border border-gray-800 p-6 md:p-8 sticky top-24 z-10">
            <h3 class="text-lg font-bold text-white border-b border-gray-700 pb-4 mb-6">Penugasan Armada (Jeep)</h3>

            @if(!in_array($pesanan->status, ['Disetujui', 'DP Lunas']))
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 text-center">
                    <svg class="w-12 h-12 text-gray-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <h4 class="text-white font-bold mb-1">Form Penugasan Terkunci</h4>
                    <p class="text-sm text-gray-400">Pesanan saat ini berstatus <span class="font-bold text-emerald-400">{{ $pesanan->status }}</span>. Anda hanya dapat mengatur armada pada saat statusnya <b>Disetujui</b> atau <b>DP Lunas</b>.</p>
                </div>
            @else
            <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST">
                @csrf
                @method('PUT')

            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status Pembayaran</label>
                <div class="w-full px-4 py-3 rounded-xl border border-gray-700 bg-gray-800 text-emerald-400 font-bold uppercase tracking-widest text-center cursor-not-allowed">
                    {{ $pesanan->status }}
                </div>
                <p class="text-[10px] text-gray-500 mt-2 text-center">Hanya Admin Pusat yang berwenang mengubah status transaksi.</p>
            </div>

            <div class="space-y-4 mb-6">
                @php
                    $totalArmada = $pesanan->jumlah_jeep > 0 ? $pesanan->jumlah_jeep : 1;
                @endphp
                
                @for ($i = 0; $i < $totalArmada; $i++)
                    @php $armadaTerpilih = $pesanan->armadas[$i] ?? null; @endphp
                    
                    <div class="p-4 bg-gray-800 rounded-2xl border border-gray-700">
                        <p class="text-[11px] font-black text-emerald-500 uppercase tracking-widest mb-3">Pilih Armada {{ $i + 1 }}</p>
                        
                        <div class="space-y-3">
                            <select name="jeep_id[]" class="w-full px-3 py-2.5 rounded-lg border border-gray-700 bg-gray-900 text-white text-sm font-medium focus:ring-2 focus:ring-emerald-500">
                                <option value="">-- Pilih Jeep --</option>
                                @foreach($jeeps as $jp)
                                    <option value="{{ $jp->id }}" {{ ($armadaTerpilih && $armadaTerpilih->jeep_id == $jp->id) ? 'selected' : '' }}>
                                        {{ $jp->nama_jeep }} ({{ $jp->nomor_polisi }})
                                    </option>
                                @endforeach
                            </select>

                            <select name="supir_id[]" class="w-full px-3 py-2.5 rounded-lg border border-gray-700 bg-gray-900 text-white text-sm font-medium focus:ring-2 focus:ring-emerald-500">
                                <option value="">-- Pilih Supir --</option>
                                @foreach($supirs as $dr)
                                    <option value="{{ $dr->id }}" {{ ($armadaTerpilih && $armadaTerpilih->supir_id == $dr->id) ? 'selected' : '' }}>
                                        {{ $dr->nama_supir }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor
            </div>

            <button type="submit" class="w-full py-4 bg-emerald-500 text-white text-base font-extrabold rounded-xl hover:bg-emerald-400 transition shadow-[0_4px_15px_rgba(16,185,129,0.3)] mb-4">
                Simpan Penugasan Armada
            </button>
            
            <p class="text-[10px] text-gray-400 text-center leading-relaxed">
                <span class="font-bold text-amber-500">Penting:</span> Pastikan Jeep dan Supir yang dipilih sedang dalam kondisi siap jalan. E-Ticket Pelanggan akan valid setelah Anda menetapkan armada ini.
            </p>
            </form>
            
            @if($pesanan->armadas->count() > 0)
                <div class="mt-6 pt-6 border-t border-gray-800">
                    <a href="{{ route('booking.print', $pesanan->id) }}" target="_blank" class="w-full py-3.5 bg-blue-600 text-white text-base font-extrabold rounded-xl hover:bg-blue-500 transition shadow-[0_4px_15px_rgba(37,99,235,0.3)] flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Surat Jalan / Manifest Armada
                    </a>
                </div>
            @endif
            @endif
        </div>
        @endif
    </div>
    </div>

</div>

@if(Auth::user()->role === 'pengelola')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateDropdowns(selector) {
        const selects = document.querySelectorAll(selector);
        
        if (selects.length <= 1) return; // Tidak perlu filter jika armada cuma 1
        
        function refreshState() {
            // Dapatkan semua nilai yang saat ini terpilih (kecuali yang kosong)
            const selectedValues = Array.from(selects)
                .map(s => s.value)
                .filter(v => v !== '');

            selects.forEach(s => {
                Array.from(s.options).forEach(option => {
                    if (option.value === '') return;
                    // Kunci (disable) opsi jika sedang dipakai di dropdown LAIN, 
                    // tapi biarkan terbuka untuk dropdown yang memang memilihnya.
                    if (selectedValues.includes(option.value) && s.value !== option.value) {
                        option.disabled = true;
                        option.text = option.text.replace(' (Terpakai)', '') + ' (Terpakai)';
                    } else {
                        option.disabled = false;
                        option.text = option.text.replace(' (Terpakai)', '');
                    }
                });
            });
        }

        selects.forEach(select => {
            select.addEventListener('change', refreshState);
        });
        
        // Inisialisasi awal saat halaman dimuat
        refreshState();
    }

    updateDropdowns('select[name="jeep_id[]"]');
    updateDropdowns('select[name="supir_id[]"]');
});
</script>
@endif
@endsection