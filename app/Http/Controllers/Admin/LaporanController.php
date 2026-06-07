<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Komunitas;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggal_selesai = $request->input('tanggal_selesai', now()->endOfMonth()->toDateString());
        
        $komunitas_id = $request->input('komunitas_id');

        $query = Pesanan::with(['user', 'paketWisata', 'komunitas', 'pembayarans'])
                    ->whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
                    ->whereIn('status', ['DP Lunas', 'Lunas', 'Selesai']);

        if ($komunitas_id) {
            $query->where('komunitas_id', $komunitas_id);
        }

        $laporan = $query->latest()->get();
        
        $total_pendapatan = \App\Models\Pembayaran::where('status', 'Valid')
            ->whereIn('pesanan_id', $laporan->pluck('id'))
            ->sum('jumlah_bayar');
        $total_transaksi = $laporan->count();
        $daftar_komunitas = Komunitas::all();

        return view('admin.laporan.index', compact(
            'laporan', 'tanggal_mulai', 'tanggal_selesai', 
            'komunitas_id', 'total_pendapatan', 'total_transaksi', 'daftar_komunitas'
        ));
    }

    public function cetak(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggal_selesai = $request->input('tanggal_selesai', now()->endOfMonth()->toDateString());
        $komunitas_id = $request->input('komunitas_id');

        $query = Pesanan::with(['user', 'paketWisata', 'komunitas', 'jeep', 'supir', 'pembayarans'])
                    ->whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
                    ->whereIn('status', ['DP Lunas', 'Lunas', 'Selesai']);

        if ($komunitas_id) {
            $query->where('komunitas_id', $komunitas_id);
        }

        $laporan = $query->orderBy('created_at', 'asc')->get();
        $total_pendapatan = \App\Models\Pembayaran::where('status', 'Valid')
            ->whereIn('pesanan_id', $laporan->pluck('id'))
            ->sum('jumlah_bayar');
        
        $nama_komunitas = $komunitas_id 
            ? (Komunitas::find($komunitas_id)?->nama_komunitas ?? 'Komunitas Tidak Ditemukan') 
            : 'Semua Komunitas';

        return view('admin.laporan.print', compact('laporan', 'tanggal_mulai', 'tanggal_selesai', 'total_pendapatan', 'nama_komunitas'));
    }

    public function komunitas(Request $request)
    {
        $tanggal_mulai = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggal_selesai = $request->input('tanggal_selesai', now()->endOfMonth()->toDateString());

        $komunitas = Komunitas::with(['pesanan' => function($q) use ($tanggal_mulai, $tanggal_selesai) {
            $q->whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
              ->whereIn('status', ['DP Lunas', 'Lunas', 'Selesai']);
        }])->get();

        // Calculate total pendapatan per komunitas
        foreach ($komunitas as $k) {
            $k->total_transaksi = $k->pesanan->count();
            $pesananIds = $k->pesanan->pluck('id');
            $k->total_pendapatan = \App\Models\Pembayaran::where('status', 'Valid')
                ->whereIn('pesanan_id', $pesananIds)
                ->sum('jumlah_bayar');
        }

        return view('admin.laporan.komunitas', compact('komunitas', 'tanggal_mulai', 'tanggal_selesai'));
    }
}