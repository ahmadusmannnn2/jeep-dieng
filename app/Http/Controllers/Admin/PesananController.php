<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\PesananArmada; // <-- Model Pivot Tambahan
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
        $query = Pesanan::with(['user', 'paketWisata', 'jadwal', 'pembayaran', 'komunitas', 'armadas.jeep', 'armadas.supir']);

        // LOGIKA MULTI-TENANT (GEMBOK PENGELOLA)
        if ($user->role === 'pengelola') {
            // Hanya tampilkan pesanan komunitasnya sendiri
            $query->where('komunitas_id', $user->komunitas_id);
        } else {
            // Jika Admin Pusat, jalankan filter dropdown
            if ($request->filled('komunitas_id')) {
                $query->where('komunitas_id', $request->komunitas_id);
            }
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Pencarian
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
        $user = Auth::user();
        // Proteksi: Pengelola dilarang melihat pesanan komunitas lain
        if ($user->role === 'pengelola' && $pesanan->komunitas_id !== $user->komunitas_id) {
            abort(403, 'Akses Ditolak: Anda tidak dapat melihat pesanan dari komunitas lain.');
        }

        // Load relasi armadas yang baru
        $pesanan->load(['user', 'paketWisata', 'armadas.jeep', 'armadas.supir', 'pembayaran', 'komunitas']);
        
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
        $user = Auth::user();
        // Proteksi: Pengelola dilarang edit pesanan (Hanya boleh lihat detail saja)
        if ($user->role === 'pengelola') {
            abort(403, 'Akses Ditolak: Pengelola hanya dapat melihat data pesanan saja.');
        }

        // Load relasi armadas beserta data jeep dan supirnya agar bisa tampil di form
        $pesanan->load('armadas.jeep', 'armadas.supir');
        
        $jeeps = Jeep::where('komunitas_id', $pesanan->komunitas_id)->get();
        $supirs = Supir::where('komunitas_id', $pesanan->komunitas_id)->get();
        
        return view('admin.pesanan.edit', compact('pesanan', 'jeeps', 'supirs'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $user = Auth::user();
        // Proteksi: Pengelola tidak diizinkan mengupdate pesanan
        if ($user->role === 'pengelola') {
            abort(403, 'Akses Ditolak: Pengelola tidak diizinkan mengubah data pesanan.');
        }

        // Validasi menggunakan Array untuk mendukung Rombongan
        $request->validate([
            'status'     => 'required|in:Pending,Disetujui,DP Lunas,Lunas,Selesai,Dibatalkan',
            'jeep_id'    => 'nullable|array', 
            'jeep_id.*'  => 'nullable|exists:jeep,id',
            'supir_id'   => 'nullable|array',
            'supir_id.*' => 'nullable|exists:supir,id',
        ]);

        $statusLama = $pesanan->status;

        // 1. Update Status Pesanan
        $pesanan->update([
            'status' => $request->status,
        ]);

        // 2. Simpan Multi-Armada ke Tabel Pivot
        // Hapus data lama terlebih dahulu agar tidak duplikat saat diupdate ulang
        $pesanan->armadas()->delete(); 
        
        if ($request->has('jeep_id')) {
            foreach ($request->jeep_id as $index => $j_id) {
                if (!empty($j_id)) {
                    PesananArmada::create([
                        'pesanan_id' => $pesanan->id,
                        'jeep_id'    => $j_id,
                        'supir_id'   => $request->supir_id[$index] ?? null,
                    ]);
                }
            }
        }

        // 3. Jika status diubah menjadi Lunas atau DP Lunas, otomatis update status pembayaran terbaru
        if (in_array($request->status, ['Lunas', 'DP Lunas']) && $pesanan->pembayaran) {
            $pesanan->pembayaran->update(['status' => 'Valid']);
        }

        // 4. 📧 Kirim email berdasarkan perubahan status
        $pesanan->load(['user', 'paketWisata', 'armadas.jeep', 'armadas.supir', 'komunitas', 'pembayaran']);

        try {
            if (in_array($request->status, ['DP Lunas', 'Lunas']) && $statusLama !== $request->status) {
                Mail::to($pesanan->user->email)->send(new PaymentConfirmedMail($pesanan));

                if ($request->status === 'Lunas') {
                    Mail::to($pesanan->user->email)->send(new EtiketLunasMail($pesanan));
                }
            }
        } catch (\Exception $e) {
            // Jangan hentikan proses jika email gagal terkirim
        }

        return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', 'Status pesanan dan penugasan armada berhasil diperbarui!');
    }
}