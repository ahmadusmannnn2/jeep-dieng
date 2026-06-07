<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Jeep;
use App\Models\Supir;
use App\Models\Pembayaran;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Pesanan::with(['user', 'paketWisata', 'jadwal', 'pembayaran']);

        // Filter data pesanan sesuai komunitas yang login
        if ($user->role === 'admin_komunitas') {
            $query->where('komunitas_id', $user->komunitas_id);
        } else {
            if ($request->filled('komunitas_id')) {
                $query->where('komunitas_id', $request->komunitas_id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%")
                       ->orWhere('no_hp', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%");
            });
        }

        $pesanan = $query->latest()->get();
        $komunitas = $user->role === 'super_admin' ? Komunitas::all() : collect();

        return view('admin.pesanan.index', compact('pesanan', 'komunitas'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['user', 'paketWisata', 'jeep', 'supir', 'pembayaran', 'komunitas']);
        
        // Ambil data armada dan supir yang berada di komunitas yang sama dengan pesanan ini
        // (Atau jika komunitas_id null, ambil semua armada/supir)
        $jeeps = Jeep::when($pesanan->komunitas_id, function($q) use ($pesanan) {
            return $q->where('komunitas_id', $pesanan->komunitas_id);
        })->get();
        
        $supirs = Supir::when($pesanan->komunitas_id, function($q) use ($pesanan) {
            return $q->where('komunitas_id', $pesanan->komunitas_id);
        })->get();

        return view('admin.pesanan.show', compact('pesanan', 'jeeps', 'supirs'));
    }

    public function edit(Pesanan $pesanan)
    {
        // Ambil data armada dan supir yang berada di komunitas yang sama dengan pesanan ini
        $jeeps = Jeep::where('komunitas_id', $pesanan->komunitas_id)->get();
        $supirs = Supir::where('komunitas_id', $pesanan->komunitas_id)->get();
        
        return view('admin.pesanan.edit', compact('pesanan', 'jeeps', 'supirs'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        // PERBAIKAN DI SINI: Ganti 'jeeps' menjadi 'jeep' dan 'supirs' menjadi 'supir'
        $request->validate([
            'status'   => 'required|in:Pending,Disetujui,DP Lunas,Lunas,Selesai,Dibatalkan',
            'jeep_id'  => 'nullable|exists:jeep,id', 
            'supir_id' => 'nullable|exists:supir,id',
        ]);

        $pesanan->update([
            'status'   => $request->status,
            'jeep_id'  => $request->jeep_id,
            'supir_id' => $request->supir_id,
        ]);

        // Jika status diubah menjadi Lunas atau DP Lunas, otomatis update status pembayaran terbaru jika ada
        if (in_array($request->status, ['Lunas', 'DP Lunas']) && $pesanan->pembayaran) {
            $pesanan->pembayaran->update(['status' => 'Valid']);
        }

        return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', 'Status pesanan dan penugasan berhasil diperbarui!');
    }
}