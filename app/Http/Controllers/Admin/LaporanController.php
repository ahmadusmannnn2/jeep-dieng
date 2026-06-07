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

        $query = Pesanan::with(['user', 'paketWisata', 'komunitas'])
                    ->whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
                    ->whereIn('status', ['Lunas', 'Selesai']);

        if ($komunitas_id) {
            $query->where('komunitas_id', $komunitas_id);
        }

        $laporan = $query->latest()->get();
        
        $total_pendapatan = $laporan->sum('total_harga');
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

        $query = Pesanan::with(['user', 'paketWisata', 'komunitas', 'jeep', 'supir'])
                    ->whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
                    ->whereIn('status', ['Lunas', 'Selesai']);

        if ($komunitas_id) {
            $query->where('komunitas_id', $komunitas_id);
        }

        $laporan = $query->orderBy('created_at', 'asc')->get();
        $total_pendapatan = $laporan->sum('total_harga');
        
        $nama_komunitas = $komunitas_id 
            ? (Komunitas::find($komunitas_id)?->nama_komunitas ?? 'Komunitas Tidak Ditemukan') 
            : 'Semua Komunitas';

        return view('admin.laporan.print', compact('laporan', 'tanggal_mulai', 'tanggal_selesai', 'total_pendapatan', 'nama_komunitas'));
    }
}