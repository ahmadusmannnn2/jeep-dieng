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
        
        // Pengelola hanya bisa lihat laporan komunitasnya sendiri
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'pengelola') {
            $komunitas_id = $user->komunitas_id;
        }

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
        
        // Pengelola hanya bisa cetak laporan komunitasnya sendiri
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'pengelola') {
            $komunitas_id = $user->komunitas_id;
        }

        $query = Pesanan::with(['user', 'paketWisata', 'komunitas', 'armadas.jeep', 'armadas.supir', 'pembayarans'])
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

    public function exportExcel(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat mengekspor laporan ke Excel.');
        }

        $tanggal_mulai = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggal_selesai = $request->input('tanggal_selesai', now()->endOfMonth()->toDateString());
        $komunitas_id = $request->input('komunitas_id');
        
        $query = Pesanan::with(['user', 'paketWisata', 'komunitas', 'pembayarans'])
                    ->whereBetween('created_at', [$tanggal_mulai . ' 00:00:00', $tanggal_selesai . ' 23:59:59'])
                    ->whereIn('status', ['DP Lunas', 'Lunas', 'Selesai']);

        if ($komunitas_id) {
            $query->where('komunitas_id', $komunitas_id);
        }

        $laporan = $query->orderBy('created_at', 'asc')->get();

        $fileName = 'Laporan_Keuangan_Jeep_Dieng_' . $tanggal_mulai . '_sd_' . $tanggal_selesai . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Tanggal Order', 'Kode Booking', 'Nama Customer', 'Komunitas', 'Paket Wisata', 'Total Terbayar (Rp)', 'Status'];

        $callback = function() use($laporan, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($laporan as $item) {
                $terbayar = $item->pembayarans->where('status', 'Valid')->sum('jumlah_bayar');
                $row['Tanggal Order']  = $item->created_at->format('Y-m-d H:i');
                $row['Kode Booking']   = 'BKG-' . str_pad($item->id, 5, '0', STR_PAD_LEFT);
                $row['Nama Customer']  = $item->user->name;
                $row['Komunitas']      = $item->komunitas->nama_komunitas ?? 'Umum';
                $row['Paket Wisata']   = $item->paketWisata->nama_paket ?? 'Paket Terhapus';
                $row['Total Terbayar (Rp)'] = $terbayar;
                $row['Status']         = $item->status;

                fputcsv($file, array($row['Tanggal Order'], $row['Kode Booking'], $row['Nama Customer'], $row['Komunitas'], $row['Paket Wisata'], $row['Total Terbayar (Rp)'], $row['Status']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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