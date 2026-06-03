<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Jeep;
use App\Models\Supir;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Pesanan::with(['user', 'paketWisata', 'jadwal', 'pembayaran']);

        // Filter data pesanan sesuai komunitas yang login
        if ($user->role === 'admin_komunitas') {
            $query->where('komunitas_id', $user->komunitas_id);
        }

        $pesanan = $query->latest()->get();
        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['user', 'paketWisata', 'jadwal', 'jeep', 'supir', 'pembayaran', 'komunitas']);
        return view('admin.pesanan.show', compact('pesanan'));
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
        $request->validate([
            'status' => 'required|in:Pending,Disetujui,Lunas,Selesai,Dibatalkan',
            'jeep_id' => 'nullable|exists:jeep,id',
            'supir_id' => 'nullable|exists:supir,id',
        ]);

        $pesanan->update([
            'status' => $request->status,
            'jeep_id' => $request->jeep_id,
            'supir_id' => $request->supir_id,
        ]);

        // Jika status diubah menjadi Lunas, otomatis update status pembayaran jika ada
        if ($request->status === 'Lunas' && $pesanan->pembayaran) {
            $pesanan->pembayaran->update(['status' => 'Valid']);
        }

        return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', 'Status pesanan dan penugasan berhasil diperbarui!');
    }
}