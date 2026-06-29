@extends('admin.layouts.app')

@section('title', 'Dashboard Admin - Jeep Dieng')
@section('header_title', 'Ringkasan Sistem')
@section('header_subtitle', 'Analisis performa data reservasi dan armada secara real-time')

@section('content')

<form method="GET" action="{{ route('admin.dashboard') }}" class="mb-8 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-end">
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Tahun</label>
        <select name="tahun" class="w-full md:w-40 px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 font-bold text-gray-800 transition">
            @for($i = date('Y'); $i >= 2024; $i--)
                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>

    @if(Auth::user()->role !== 'pengelola')
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Filter Komunitas</label>
        <select name="komunitas_id" class="w-full md:w-64 px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 font-bold text-gray-800 transition">
            <option value="">-- Semua Komunitas --</option>
            @foreach($daftarKomunitas as $kom)
                <option value="{{ $kom->id }}" {{ $komunitasId == $kom->id ? 'selected' : '' }}>{{ $kom->nama_komunitas }}</option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="flex gap-2">
        <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-emerald-500 transition shadow-md">Terapkan Filter</button>
        @if(request()->has('tahun') || request()->has('komunitas_id'))
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 bg-gray-100 text-gray-500 font-bold rounded-xl hover:bg-gray-200 transition">Reset</a>
        @endif
    </div>
</form>

<div class="mb-6">
    <h3 class="text-xl font-black text-gray-800">Menampilkan Data: <span class="text-emerald-500">{{ $namaKomunitasFilter }}</span> (Tahun {{ $tahun }})</h3>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <a href="{{ route('admin.laporan.index') }}" class="block bg-gradient-to-br from-gray-900 to-gray-800 text-white p-6 rounded-3xl shadow-sm relative overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
        <div class="absolute right-[-10px] bottom-[-10px] text-white/5 transform group-hover:scale-110 transition duration-300">
            <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-1 group-hover:text-gray-300 transition">Total Pendapatan</p>
        <h4 class="text-2xl font-black text-emerald-400">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
        <p class="text-[11px] text-gray-400 mt-2">*Status Lunas/Selesai</p>
    </a>

    <a href="{{ route('admin.pesanan.index') }}" class="block bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between group hover:border-amber-300 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <p class="text-xs uppercase tracking-widest font-bold text-gray-400 group-hover:text-gray-600 transition">Butuh Persetujuan</p>
                @if($pesananPending > 0)
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                @endif
            </div>
            <h4 class="text-3xl font-black text-gray-900">{{ $pesananPending }}</h4>
            <p class="text-[11px] text-amber-600 font-bold mt-2">Pesanan menunggu</p>
        </div>
        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </a>

    <a href="{{ route('admin.jeep.index') }}" class="block bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between group hover:border-emerald-300 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div>
            <p class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-1 group-hover:text-gray-600 transition">Total Jeep</p>
            <h4 class="text-3xl font-black text-gray-900">{{ $totalJeep }}</h4>
            <p class="text-[11px] text-gray-500 mt-2">Armada terdaftar</p>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
    </a>

    <a href="{{ route('admin.supir.index') }}" class="block bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between group hover:border-emerald-300 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
        <div>
            <p class="text-xs uppercase tracking-widest font-bold text-gray-400 mb-1 group-hover:text-gray-600 transition">Total Supir</p>
            <h4 class="text-3xl font-black text-gray-900">{{ $totalSupir }}</h4>
            <p class="text-[11px] text-gray-500 mt-2">Driver aktif lapangan</p>
        </div>
        <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
    </a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Grafik Pendapatan Tahun {{ $tahun }}</h3>
        <div class="w-full h-72">
            <canvas id="grafikPendapatan"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900">Pesanan Terbaru</h3>
            <a href="{{ route('admin.pesanan.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl transition">Lihat Semua</a>
        </div>
        
        <div class="space-y-4">
            @forelse($pesananTerbaru as $item)
                <div class="flex justify-between items-center p-3 rounded-2xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition">
                    <div>
                        <p class="font-bold text-sm text-gray-900">#BKG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-xs text-emerald-600 font-bold mb-0.5">Tour: {{ \Carbon\Carbon::parse($item->tanggal_jadwal)->format('d M Y') }}</p>
                        <p class="text-[11px] text-gray-500 truncate max-w-[150px]">{{ $item->user->name }} - {{ $item->komunitas->nama_komunitas ?? 'Umum' }}</p>
                    </div>
                    <div class="text-right">
                        <!-- Tampilkan Jumlah Armada -->
                        <p class="text-[10px] font-bold text-gray-500 mb-1">{{ $item->armadas->count() }} Jeep Ditugaskan</p>
                        @if($item->armadas->count() > 0)
                            <div class="text-[9px] text-gray-600 text-right mt-1 max-w-[200px] truncate space-y-0.5">
                                @foreach($item->armadas as $armada)
                                    <div>🚘 {{ $armada->jeep->nama_jeep ?? 'Jeep' }} - 👤 {{ $armada->supir->nama_supir ?? 'Supir' }}</div>
                                @endforeach
                            </div>
                        @endif
                        @if($item->status === 'Pending')
                            <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-lg text-[10px] font-bold">Menunggu</span>
                        @elseif($item->status === 'DP Lunas')
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-bold">DP Lunas</span>
                        @elseif($item->status === 'Lunas')
                            <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-bold">Lunas</span>
                        @elseif($item->status === 'Selesai')
                            <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-bold">Selesai</span>
                        @else
                            <span class="px-2 py-1 bg-red-50 text-red-700 rounded-lg text-[10px] font-bold">Batal</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada pesanan.</p>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('grafikPendapatan').getContext('2d');
        const dataPHP = @json($dataGrafik);
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, '#10b981');
        gradient.addColorStop(1, '#6ee7b7');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dataPHP,
                    backgroundColor: gradient,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if(value === 0) return '0';
                                return 'Rp ' + (value/1000) + 'K';
                            }
                        },
                        grid: { borderDash: [4, 4], color: '#f3f4f6' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection