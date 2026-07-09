<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Jeep;
use App\Models\Supir;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. Ambil Parameter Filter Tahun (Default: Tahun ini)
        $tahun = $request->input('tahun', date('Y'));
        
        // 2. LOGIKA MULTI-TENANT (GEMBOK PENGELOLA)
        if ($user->role === 'pengelola') {
            // Jika yang login pengelola, PAKSA komunitas_id sesuai akunnya. Abaikan filter request.
            $komunitasId = $user->komunitas_id;
        } else {
            // Jika admin pusat, boleh pakai filter dari dropdown
            $komunitasId = $request->input('komunitas_id'); 
        }

        // 3. Siapkan Query Dasar dengan Filter
        $pesananQuery = Pesanan::query()->whereYear('created_at', $tahun);
        $jeepQuery = Jeep::query();
        $supirQuery = Supir::query();
        
        // Terapkan filter komunitas jika ada (Untuk pengelola, ini PASTI selalu jalan)
        if ($komunitasId) {
            $pesananQuery->where('komunitas_id', $komunitasId);
            $jeepQuery->where('komunitas_id', $komunitasId);
            $supirQuery->where('komunitas_id', $komunitasId);
        }
        
        // 4. Hitung Metrik Data Statistik (Card)
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
        
        // Load relasi armadas di sini agar tidak error di view
        $pesananTerbaru = (clone $pesananQuery)->with(['user', 'paketWisata', 'komunitas', 'armadas.jeep', 'armadas.supir'])->latest()->take(5)->get();

        // 5. DATA GRAFIK: Hitung Total Pendapatan per Bulan di Tahun Terpilih
        $grafikRaw = \App\Models\Pembayaran::where('pembayaran.status', 'Valid')
            ->join('pesanan', 'pembayaran.pesanan_id', '=', 'pesanan.id')
            ->whereYear('pembayaran.created_at', $tahun)
            ->when($komunitasId, function($q) use ($komunitasId) {
                $q->where('pesanan.komunitas_id', $komunitasId);
            })
            ->select(
                DB::raw('MONTH(pembayaran.created_at) as bulan'),
                'pesanan.komunitas_id',
                DB::raw('SUM(pembayaran.jumlah_bayar) as total')
            )
            ->groupBy('bulan', 'pesanan.komunitas_id')
            ->get();

        $daftarKomunitas = Komunitas::all();
        $datasetsGrafik = [];
        $warnaGrafik = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316', '#64748b'];

        if ($komunitasId) {
            $kom = $daftarKomunitas->firstWhere('id', $komunitasId);
            $namaLabel = $kom ? $kom->nama_komunitas : 'Komunitas';
            $dataBulan = array_fill(1, 12, 0);
            foreach ($grafikRaw as $row) {
                $dataBulan[$row->bulan] = $row->total;
            }
            $datasetsGrafik[] = [
                'label' => $namaLabel,
                'data' => array_values($dataBulan),
                'backgroundColor' => '#10b981', // emerald
                'borderRadius' => 4,
            ];
        } else {
            // Semua komunitas
            $colorIndex = 0;
            // Tambahkan 1 dataset extra untuk pesanan tanpa komunitas (jika ada)
            $komunitasGroups = $grafikRaw->groupBy('komunitas_id');
            
            foreach ($komunitasGroups as $k_id => $rows) {
                $kom = $daftarKomunitas->firstWhere('id', $k_id);
                $namaLabel = $kom ? $kom->nama_komunitas : 'Umum (Tanpa Komunitas)';
                
                $dataBulan = array_fill(1, 12, 0);
                foreach ($rows as $row) {
                    $dataBulan[$row->bulan] = $row->total;
                }
                
                $datasetsGrafik[] = [
                    'label' => $namaLabel,
                    'data' => array_values($dataBulan),
                    'backgroundColor' => $warnaGrafik[$colorIndex % count($warnaGrafik)],
                    'borderRadius' => 4,
                ];
                $colorIndex++;
            }
        }

        $namaKomunitasFilter = $komunitasId 
            ? ($daftarKomunitas->firstWhere('id', $komunitasId)?->nama_komunitas ?? 'Komunitas Tidak Ditemukan')
            : 'Semua Komunitas';

        // Hitung Pendapatan Admin dari Potongan 10% dan Total Penarikan (Hanya untuk Admin)
        $adminPendapatanFee = 0;
        $adminTotalPenarikan = 0;
        
        // Metrik khusus pengelola
        $pengelolaPendapatanKotor = 0;
        $pengelolaPotonganAdmin = 0;
        $pengelolaPendapatanBersih = 0;
        $pengelolaPesananNeedJeep = 0;

        if ($user->role === 'admin') {
            $adminPendapatanFee = \App\Models\PenarikanSaldo::where('status', 'Selesai')
                ->whereMonth('updated_at', date('m'))
                ->whereYear('updated_at', date('Y'))
                ->sum('potongan_admin');
                
            $adminTotalPenarikan = \App\Models\PenarikanSaldo::where('status', 'Selesai')
                ->whereMonth('updated_at', date('m'))
                ->whereYear('updated_at', date('Y'))
                ->sum('total_diterima');
        } else {
            // Hitung pendapatan khusus pengelola (dari pesanan DP Lunas, Lunas, Selesai)
            $pengelolaPendapatanKotor = $totalPendapatan;
            $pengelolaPotonganAdmin = $pengelolaPendapatanKotor * 0.10;
            $pengelolaPendapatanBersih = $pengelolaPendapatanKotor - $pengelolaPotonganAdmin;
            
            // Pesanan yang butuh armada
            $pengelolaPesananNeedJeep = (clone $pesananQuery)
                ->where('status', 'DP Lunas')
                ->doesntHave('armadas')
                ->count();
        }

        return view('admin.dashboard', compact(
            'totalPesanan', 'pesananPending', 'pesananLunas', 'totalPendapatan', 
            'totalJeep', 'totalSupir', 'pesananTerbaru',
            'datasetsGrafik', 'tahun', 'komunitasId', 'daftarKomunitas', 'namaKomunitasFilter',
            'adminPendapatanFee', 'adminTotalPenarikan',
            'pengelolaPendapatanKotor', 'pengelolaPotonganAdmin', 'pengelolaPendapatanBersih', 'pengelolaPesananNeedJeep'
        ));
    }

    public function markNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}