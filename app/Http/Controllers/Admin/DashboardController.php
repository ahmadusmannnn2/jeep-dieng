<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Jeep;
use App\Models\Supir;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Parameter Filter (Default: Tahun ini)
        $tahun = $request->input('tahun', date('Y'));
        
        // Komunitas ID murni diambil dari pilihan dropdown, bebas untuk semua admin
        $komunitasId = $request->input('komunitas_id'); 

        // 2. Siapkan Query Dasar dengan Filter
        $pesananQuery = Pesanan::query()->whereYear('created_at', $tahun);
        $jeepQuery = Jeep::query();
        $supirQuery = Supir::query();
        
        if ($komunitasId) {
            $pesananQuery->where('komunitas_id', $komunitasId);
            $jeepQuery->where('komunitas_id', $komunitasId);
            $supirQuery->where('komunitas_id', $komunitasId);
        }
        
        // 3. Hitung Metrik Data Statistik (Card)
        $totalPesanan = (clone $pesananQuery)->count();
        $pesananPending = (clone $pesananQuery)->where('status', 'Pending')->count();
        $pesananLunas = (clone $pesananQuery)->whereIn('status', ['DP Lunas', 'Lunas', 'Selesai'])->count();
        
        $totalPendapatan = \App\Models\Pembayaran::where('status', 'Valid')
            ->whereHas('pesanan', function($q) use ($tahun, $komunitasId) {
                $q->whereYear('created_at', $tahun);
                if ($komunitasId) {
                    $q->where('komunitas_id', $komunitasId);
                }
            })->sum('jumlah_bayar');
                            
        $totalJeep = $jeepQuery->count();
        $totalSupir = $supirQuery->count();
        
        $pesananTerbaru = (clone $pesananQuery)->with(['user', 'paketWisata', 'komunitas'])->latest()->take(5)->get();

        // 4. DATA GRAFIK: Hitung Total Pendapatan per Bulan di Tahun Terpilih (Berdasarkan Pembayaran Valid)
        $grafikPendapatan = \App\Models\Pembayaran::where('status', 'Valid')
            ->whereYear('created_at', $tahun)
            ->when($komunitasId, function($q) use ($komunitasId) {
                $q->whereHas('pesanan', function($pq) use ($komunitasId) {
                    $pq->where('komunitas_id', $komunitasId);
                });
            })
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(jumlah_bayar) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $dataGrafik = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataGrafik[] = $grafikPendapatan[$i] ?? 0;
        }

        $daftarKomunitas = Komunitas::all();
        $namaKomunitasFilter = $komunitasId 
            ? (Komunitas::find($komunitasId)?->nama_komunitas ?? 'Komunitas Tidak Ditemukan')
            : 'Semua Komunitas';

        return view('admin.dashboard', compact(
            'totalPesanan', 'pesananPending', 'pesananLunas', 'totalPendapatan', 
            'totalJeep', 'totalSupir', 'pesananTerbaru',
            'dataGrafik', 'tahun', 'komunitasId', 'daftarKomunitas', 'namaKomunitasFilter'
        ));
    }
}