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
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmedMail;
use App\Mail\EtiketLunasMail;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Pesanan::with(['user', 'paketWisata', 'jadwal', 'pembayaran', 'komunitas']);

        if ($request->filled('komunitas_id')) {
            $query->where('komunitas_id', $request->komunitas_id);
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
        $komunitas = Komunitas::all();

        return view('admin.pesanan.index', compact('pesanan', 'komunitas'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['user', 'paketWisata', 'jeep', 'supir', 'pembayaran', 'komunitas']);
        
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
        $jeeps = Jeep::where('komunitas_id', $pesanan->komunitas_id)->get();
        $supirs = Supir::where('komunitas_id', $pesanan->komunitas_id)->get();
        
        return view('admin.pesanan.edit', compact('pesanan', 'jeeps', 'supirs'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status'   => 'required|in:Pending,Disetujui,DP Lunas,Lunas,Selesai,Dibatalkan',
            'jeep_id'  => 'nullable|exists:jeep,id', 
            'supir_id' => 'nullable|exists:supir,id',
        ]);

        $statusLama = $pesanan->status;

        $pesanan->update([
            'status'   => $request->status,
            'jeep_id'  => $request->jeep_id,
            'supir_id' => $request->supir_id,
        ]);

        // Jika status diubah menjadi Lunas atau DP Lunas, otomatis update status pembayaran terbaru
        if (in_array($request->status, ['Lunas', 'DP Lunas']) && $pesanan->pembayaran) {
            $pesanan->pembayaran->update(['status' => 'Valid']);
        }

        // 📧 Kirim email berdasarkan perubahan status
        $pesanan->load(['user', 'paketWisata', 'jeep', 'supir', 'komunitas', 'pembayaran']);

        try {
            // Kirim email konfirmasi DP atau Lunas ke customer
            if (in_array($request->status, ['DP Lunas', 'Lunas']) && $statusLama !== $request->status) {
                Mail::to($pesanan->user->email)->send(new PaymentConfirmedMail($pesanan));

                // Jika LUNAS, kirim juga E-Tiket resmi
                if ($request->status === 'Lunas') {
                    Mail::to($pesanan->user->email)->send(new EtiketLunasMail($pesanan));
                }
            }
        } catch (\Exception $e) {
            // Jangan hentikan proses jika email gagal terkirim
        }

        return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', 'Status pesanan dan penugasan berhasil diperbarui!');
    }
}