<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Models\Jeep;
use App\Models\Supir;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $queryPesanan = Pesanan::query();
        $queryJeep = Jeep::query();
        $querySupir = Supir::query();

        // LOGIKA MULTI-TENANT: Jika dia admin komunitas, filter data hanya milik komunitasnya
        if ($user->role === 'admin_komunitas') {
            $queryPesanan->where('komunitas_id', $user->komunitas_id);
            $queryJeep->where('komunitas_id', $user->komunitas_id);
            $querySupir->where('komunitas_id', $user->komunitas_id);
        }

        $totalPesanan = $queryPesanan->count();
        $totalJeep = $queryJeep->count();
        $totalSupir = $querySupir->count();
        $totalCustomer = User::where('role', 'customer')->count();

        // Kita juga kirim nama komunitas ke tampilan
        $namaKomunitas = $user->role === 'super_admin' ? 'Semua Komunitas (Super Admin)' : $user->komunitas->nama_komunitas;

        return view('admin.dashboard', compact('totalPesanan', 'totalJeep', 'totalSupir', 'totalCustomer', 'namaKomunitas'));
    }
}