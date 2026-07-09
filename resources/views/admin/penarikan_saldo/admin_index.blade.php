@extends('admin.layouts.app')

@section('title', 'Permintaan Penarikan Saldo - Admin')
@section('header_title', 'Permintaan Penarikan Saldo')
@section('header_subtitle', 'Kelola pengajuan pencairan dana dari pengelola.')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-800">Daftar Pengajuan Penarikan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-100">
                    <th class="py-4 px-6 font-semibold">Komunitas / Pengelola</th>
                    <th class="py-4 px-6 font-semibold">Rekening Bank</th>
                    <th class="py-4 px-6 font-semibold text-right">Total Pendapatan</th>
                    <th class="py-4 px-6 font-semibold text-right">Potongan 10%</th>
                    <th class="py-4 px-6 font-semibold text-right">Total Ditransfer</th>
                    <th class="py-4 px-6 font-semibold text-center">Status</th>
                    <th class="py-4 px-6 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($penarikan as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-4 px-6 text-gray-800">
                        <span class="font-bold">{{ $item->komunitas->nama_komunitas ?? '-' }}</span><br>
                        <span class="text-xs text-gray-500">Diajukan: {{ $item->created_at->format('d M Y H:i') }}</span>
                    </td>
                    <td class="py-4 px-6 text-gray-800">
                        <span class="font-semibold">{{ $item->komunitas->nama_bank ?? 'Belum Diatur' }}</span><br>
                        <span class="text-gray-600">{{ $item->komunitas->no_rekening ?? '-' }}</span><br>
                        <span class="text-xs text-gray-500">a/n {{ $item->komunitas->atas_nama ?? '-' }}</span>
                    </td>
                    <td class="py-4 px-6 text-right text-gray-600">
                        Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-right text-emerald-500 font-semibold">
                        Rp {{ number_format($item->potongan_admin, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-right font-bold text-gray-800 text-base">
                        Rp {{ number_format($item->total_diterima, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($item->status == 'Diajukan')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Menunggu Transfer</span>
                        @elseif($item->status == 'Selesai')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Selesai</span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($item->status == 'Diajukan')
                            <button onclick="bukaModalProses({{ $item->id }})" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg shadow transition">
                                Proses Pencairan
                            </button>
                        @elseif($item->bukti_transfer)
                            <a href="{{ Storage::url($item->bukti_transfer) }}" target="_blank" class="text-emerald-500 hover:text-emerald-600 text-sm font-semibold flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Bukti Transfer
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 px-6 text-center text-gray-500">
                        Tidak ada pengajuan penarikan dana.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Proses Pencairan -->
<div id="modalProses" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 relative z-10 transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
            <h3 class="text-lg font-bold text-gray-900">Upload Bukti Transfer</h3>
            <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="formProses" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <p class="text-sm text-gray-600 mb-4">Pastikan Anda telah mentransfer dana ke rekening pengelola yang tertera, lalu unggah bukti transfer di bawah ini.</p>
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Foto Bukti Transfer</label>
                <input type="file" name="bukti_transfer" required accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm">
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="tutupModal()" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 rounded-xl shadow transition">Proses Selesai</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function bukaModalProses(id) {
        document.getElementById('modalProses').classList.remove('hidden');
        document.getElementById('formProses').action = `/admin/penarikan-saldo/${id}`;
    }

    function tutupModal() {
        document.getElementById('modalProses').classList.add('hidden');
    }
</script>
@endpush
