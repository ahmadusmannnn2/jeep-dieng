@extends('admin.layouts.app')

@section('title', 'Penarikan Saldo - Pengelola')
@section('header_title', 'Penarikan Saldo')
@section('header_subtitle', 'Tarik dana dari pesanan yang telah selesai.')

@section('content')
<div x-data="{ tab: 'tersedia' }" class="space-y-6">

    <!-- Tab Navigation -->
    <div class="flex space-x-1 bg-white p-1 rounded-xl shadow-sm border border-gray-100 max-w-md">
        <button @click="tab = 'tersedia'" :class="{ 'bg-emerald-50 text-emerald-600 font-bold': tab === 'tersedia', 'text-gray-500 hover:text-gray-700': tab !== 'tersedia' }" class="flex-1 py-2.5 px-4 rounded-lg text-sm transition-all focus:outline-none">
            Saldo Tersedia
        </button>
        <button @click="tab = 'riwayat'" :class="{ 'bg-emerald-50 text-emerald-600 font-bold': tab === 'riwayat', 'text-gray-500 hover:text-gray-700': tab !== 'riwayat' }" class="flex-1 py-2.5 px-4 rounded-lg text-sm transition-all focus:outline-none">
            Riwayat Penarikan
        </button>
    </div>

    <!-- Tab Saldo Tersedia -->
    <div x-show="tab === 'tersedia'" x-transition class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Pesanan Selesai</h3>
                <p class="text-sm text-gray-500 mt-1">Pilih pesanan yang ingin Anda tarik dananya.</p>
            </div>
        </div>
        
        <form action="{{ route('admin.penarikan-saldo.store') }}" method="POST" id="form-penarikan">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-100">
                            <th class="py-4 px-6 font-semibold w-12 text-center">
                                <input type="checkbox" id="check-all" class="w-4 h-4 text-emerald-500 rounded border-gray-300 focus:ring-emerald-500 cursor-pointer">
                            </th>
                            <th class="py-4 px-6 font-semibold">ID Pesanan</th>
                            <th class="py-4 px-6 font-semibold">Paket Wisata</th>
                            <th class="py-4 px-6 font-semibold text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($saldoTersedia as $pesanan)
                        <tr class="hover:bg-gray-50 transition group">
                            <td class="py-4 px-6 text-center">
                                <input type="checkbox" name="pesanan_ids[]" value="{{ $pesanan->id }}" class="pesanan-checkbox w-4 h-4 text-emerald-500 rounded border-gray-300 focus:ring-emerald-500 cursor-pointer" data-nominal="{{ $pesanan->pembayaran ? $pesanan->pembayaran->jumlah_bayar : 0 }}">
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-800">
                                #{{ $pesanan->id }}<br>
                                <span class="text-xs text-gray-500">{{ $pesanan->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="py-4 px-6 text-gray-600">
                                {{ $pesanan->paketWisata->nama_paket ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-right font-semibold text-emerald-600">
                                Rp {{ number_format($pesanan->pembayaran ? $pesanan->pembayaran->jumlah_bayar : 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p>Tidak ada saldo yang bisa ditarik saat ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($saldoTersedia->count() > 0)
            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="w-full md:w-auto">
                        <p class="text-sm text-gray-500">Total Dipilih: <span id="text-total-dipilih" class="font-bold text-gray-800">0</span> Pesanan</p>
                        <div class="mt-2 space-y-1">
                            <p class="text-sm text-gray-600 flex justify-between gap-4"><span>Total Pendapatan:</span> <span id="text-total-pendapatan" class="font-semibold text-gray-800">Rp 0</span></p>
                            <p class="text-sm text-gray-600 flex justify-between gap-4"><span>Potongan Admin (10%):</span> <span id="text-potongan" class="font-semibold text-red-500">- Rp 0</span></p>
                            <p class="text-base text-gray-800 flex justify-between gap-4 border-t border-gray-200 pt-1 mt-1">
                                <span class="font-bold">Total Diterima:</span> 
                                <span id="text-total-diterima" class="font-bold text-emerald-600 text-lg">Rp 0</span>
                            </p>
                        </div>
                    </div>
                    <button type="submit" id="btn-ajukan" disabled class="w-full md:w-auto px-6 py-3 bg-emerald-500 hover:bg-emerald-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-md transition">
                        Ajukan Penarikan
                    </button>
                </div>
            </div>
            @endif
        </form>
    </div>

    <!-- Tab Riwayat Penarikan -->
    <div x-show="tab === 'riwayat'" x-transition style="display: none;" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Riwayat Penarikan Saldo</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-100">
                        <th class="py-4 px-6 font-semibold">Tanggal Diajukan</th>
                        <th class="py-4 px-6 font-semibold text-right">Total Pendapatan</th>
                        <th class="py-4 px-6 font-semibold text-right">Potongan 10%</th>
                        <th class="py-4 px-6 font-semibold text-right">Total Diterima</th>
                        <th class="py-4 px-6 font-semibold text-center">Status</th>
                        <th class="py-4 px-6 font-semibold text-center">Bukti Transfer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($riwayatPenarikan as $riwayat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-gray-800 font-medium">
                            {{ $riwayat->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="py-4 px-6 text-right text-gray-600">
                            Rp {{ number_format($riwayat->total_pendapatan, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-right text-red-500">
                            Rp {{ number_format($riwayat->potongan_admin, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-right font-bold text-emerald-600">
                            Rp {{ number_format($riwayat->total_diterima, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($riwayat->status == 'Diajukan')
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Menunggu Transfer</span>
                            @elseif($riwayat->status == 'Ditransfer')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Dana Ditransfer</span>
                                <form action="{{ route('penarikan-saldo.confirm', $riwayat->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Apakah Anda yakin telah menerima dana penarikan ini sesuai bukti transfer?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg shadow-sm w-full transition">Konfirmasi Terima</button>
                                </form>
                            @elseif($riwayat->status == 'Selesai')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Selesai</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">{{ $riwayat->status }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($riwayat->bukti_transfer)
                                <a href="{{ Storage::url($riwayat->bukti_transfer) }}" target="_blank" class="text-emerald-500 hover:text-emerald-600 text-sm font-semibold flex items-center justify-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat Bukti
                                </a>
                            @else
                                <span class="text-gray-400 text-xs italic">Belum ada</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 px-6 text-center text-gray-500">
                            Tidak ada riwayat penarikan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.pesanan-checkbox');
        const btnAjukan = document.getElementById('btn-ajukan');
        
        const txtTotalDipilih = document.getElementById('text-total-dipilih');
        const txtTotalPendapatan = document.getElementById('text-total-pendapatan');
        const txtPotongan = document.getElementById('text-potongan');
        const txtTotalDiterima = document.getElementById('text-total-diterima');

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }

        function calculate() {
            let count = 0;
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    count++;
                    total += parseFloat(cb.dataset.nominal);
                }
            });

            let potongan = total * 0.10;
            let diterima = total - potongan;

            txtTotalDipilih.textContent = count;
            txtTotalPendapatan.textContent = formatRupiah(total);
            txtPotongan.textContent = '- ' + formatRupiah(potongan);
            txtTotalDiterima.textContent = formatRupiah(diterima);

            if (count > 0) {
                btnAjukan.removeAttribute('disabled');
            } else {
                btnAjukan.setAttribute('disabled', 'disabled');
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = checkAll.checked;
                });
                calculate();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                const someChecked = Array.from(checkboxes).some(c => c.checked);
                
                checkAll.checked = allChecked;
                if (someChecked && !allChecked) {
                    checkAll.indeterminate = true;
                } else {
                    checkAll.indeterminate = false;
                }
                
                calculate();
            });
        });
    });
</script>
@endpush
