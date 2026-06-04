<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // Halaman Utama Laporan di Panel Admin
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Atur filter default (dari tanggal 1 bulan ini sampai tanggal hari ini jika kosong)
        $tanggal_mulai = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggal_selesai = $request->input('tanggal_selesai', now()->endOfMonth()->toDateString());
        $komunitas_id = $request->input('komunitas_id');

        if ($user->role === 'admin_komunitas') {
            $komunitas_id = $user->komunitas_id;
        }

        // Query data pesanan yang valid (Lunas atau Selesai) dalam rentang tanggal
        $query = Pesanan::with(['user', 'paketWisata', 'komunitas'])
                    ->whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
                    ->whereIn('status', ['Lunas', 'Selesai']);

        if ($komunitas_id) {
            $query->where('komunitas_id', $komunitas_id);
        }

        $laporan = $query->latest()->get();
        
        // Hitung total ringkasan
        $total_pendapatan = $laporan->sum('total_harga');
        $total_transaksi = $laporan->count();

        $daftar_komunitas = Komunitas::all();

        return view('admin.laporan.index', compact(
            'laporan', 'tanggal_mulai', 'tanggal_selesai', 
            'komunitas_id', 'total_pendapatan', 'total_transaksi', 'daftar_komunitas'
        ));
    }

    // Halaman Khusus Bebas Navigasi untuk Trigger Cetak Printer / PDF Browser
    public function cetak(Request $request)
    {
        $user = Auth::user();
        
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_selesai = $request->input('tanggal_selesai');
        $komunitas_id = $request->input('komunitas_id');

        if ($user->role === 'admin_komunitas') {
            $komunitas_id = $user->komunitas_id;
        }

        $query = Pesanan::with(['user', 'paketWisata', 'komunitas', 'jeep', 'supir'])
                    ->whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
                    ->whereIn('status', ['Lunas', 'Selesai']);

        if ($komunitas_id) {
            $query->where('komunitas_id', $komunitas_id);
        }

        // Urutkan dari transaksi paling lama ke paling baru untuk keteraturan pembukuan
        $laporan = $query->orderBy('created_at', 'asc')->get();
        $total_pendapatan = $laporan->sum('total_harga');
        
        $nama_komunitas = $komunitas_id ? Komunitas::find($komunitas_id)->nama_komunitas : 'Semua Komunitas';

        return view('admin.laporan.print', compact('laporan', 'tanggal_mulai', 'tanggal_selesai', 'total_pendapatan', 'nama_komunitas'));
    }
}