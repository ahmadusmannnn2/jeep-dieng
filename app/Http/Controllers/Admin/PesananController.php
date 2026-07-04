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
        
        $tanggalJadwal = $pesanan->tanggal_jadwal;

        // Cari ID Jeep yang sudah dipakai di pesanan aktif lain pada hari yang sama
        $bookedJeepIds = \App\Models\PesananArmada::whereHas('pesanan', function($q) use ($tanggalJadwal, $pesanan) {
            $q->whereDate('tanggal_jadwal', $tanggalJadwal)
              ->where('id', '!=', $pesanan->id)
              ->whereIn('status', ['Disetujui', 'DP Lunas', 'Selesai Perjalanan', 'Lunas']);
        })->pluck('jeep_id')->toArray();

        // Cari ID Supir yang sudah dipakai di pesanan aktif lain pada hari yang sama
        $bookedSupirIds = \App\Models\PesananArmada::whereNotNull('supir_id')
            ->whereHas('pesanan', function($q) use ($tanggalJadwal, $pesanan) {
                $q->whereDate('tanggal_jadwal', $tanggalJadwal)
                  ->where('id', '!=', $pesanan->id)
                  ->whereIn('status', ['Disetujui', 'DP Lunas', 'Selesai Perjalanan', 'Lunas']);
        })->pluck('supir_id')->toArray();

        $jeeps = Jeep::when($pesanan->komunitas_id, function($q) use ($pesanan) {
            return $q->where('komunitas_id', $pesanan->komunitas_id);
        })->whereNotIn('id', $bookedJeepIds)->get();
        
        $supirs = Supir::when($pesanan->komunitas_id, function($q) use ($pesanan) {
            return $q->where('komunitas_id', $pesanan->komunitas_id);
        })->whereNotIn('id', $bookedSupirIds)->get();
        
        $semuaKomunitas = \App\Models\Komunitas::orderBy('nama_komunitas')->get();

        return view('admin.pesanan.show', compact('pesanan', 'jeeps', 'supirs', 'semuaKomunitas'));
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
        $statusLama = $pesanan->status;

        if ($user->role === 'admin') {
            // Cabang Admin: Mengelola Status Keuangan dan Memilih Komunitas
            $request->validate([
                'status'       => 'required|in:Pending,Disetujui,DP Lunas,Lunas,Selesai,Dibatalkan',
                'komunitas_id' => 'nullable|exists:komunitas,id',
            ]);

            $pesanan->update([
                'status'       => $request->status,
                'komunitas_id' => $request->has('komunitas_id') ? $request->komunitas_id : $pesanan->komunitas_id,
            ]);

            // Jika status diubah menjadi Lunas atau DP Lunas, otomatis update status pembayaran
            if (in_array($request->status, ['Lunas', 'DP Lunas']) && $pesanan->pembayaran) {
                $pesanan->pembayaran->update(['status' => 'Valid']);
            }

            // Kirim notifikasi email
            $pesanan->load(['user', 'paketWisata', 'armadas.jeep', 'armadas.supir', 'komunitas', 'pembayaran']);
            try {
                if (in_array($request->status, ['DP Lunas', 'Lunas']) && $statusLama !== $request->status) {
                    Mail::to($pesanan->user->email)->send(new PaymentConfirmedMail($pesanan));

                    if ($request->status === 'Lunas') {
                        Mail::to($pesanan->user->email)->send(new EtiketLunasMail($pesanan));
                    }
                }
            } catch (\Exception $e) { }

            return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', 'Status pesanan dan penugasan komunitas berhasil diperbarui!');
        } 
        
        if ($user->role === 'pengelola') {
            // Cabang Pengelola: MURNI mengelola penugasan Jeep dan Supir
            $request->validate([
                'jeep_id'    => 'nullable|array', 
                'jeep_id.*'  => 'nullable|exists:jeep,id',
                'supir_id'   => 'nullable|array',
                'supir_id.*' => 'nullable|exists:supir,id',
            ]);

            // VALIDASI ARMADA/SUPIR GANDA DI FORM YANG SAMA
            $jeeps = array_filter($request->jeep_id ?? []);
            if (count($jeeps) !== count(array_unique($jeeps))) {
                return back()->with('error', 'PENUGASAN DITOLAK: Satu armada Jeep tidak boleh dipilih lebih dari satu kali dalam satu pesanan!');
            }
            
            $supirs = array_filter($request->supir_id ?? []);
            if (count($supirs) !== count(array_unique($supirs))) {
                return back()->with('error', 'PENUGASAN DITOLAK: Satu Supir tidak boleh dipilih lebih dari satu kali dalam satu pesanan!');
            }

            // VALIDASI DOUBLE BOOKING JEEP LINTAS PESANAN
            $tanggalJadwal = $pesanan->tanggal_jadwal;
            if ($request->has('jeep_id')) {
                foreach ($request->jeep_id as $j_id) {
                    if (!empty($j_id)) {
                        $isBooked = \App\Models\PesananArmada::where('jeep_id', $j_id)
                            ->whereHas('pesanan', function($query) use ($tanggalJadwal, $pesanan) {
                                $query->whereDate('tanggal_jadwal', $tanggalJadwal)
                                      ->where('id', '!=', $pesanan->id) // Abaikan pesanan ini sendiri
                                      ->whereIn('status', ['Disetujui', 'DP Lunas', 'Selesai Perjalanan', 'Lunas']); // Status aktif
                            })
                            ->exists();

                        if ($isBooked) {
                            $jeep = \App\Models\Jeep::find($j_id);
                            return back()->with('error', 'PENUGASAN DITOLAK: Armada Jeep "' . ($jeep->nama_jeep ?? $j_id) . '" sudah dipesan untuk rombongan lain pada tanggal ' . \Carbon\Carbon::parse($tanggalJadwal)->format('d/m/Y') . '. Silakan pilih Jeep yang nganggur.');
                        }
                    }
                }
            }

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

            return redirect()->route('admin.pesanan.show', $pesanan->id)->with('success', 'Penugasan armada Jeep dan Supir berhasil disimpan!');
        }

        abort(403, 'Akses Ditolak: Peran pengguna tidak dikenali.');
    }

    public function markSelesaiPerjalanan(Pesanan $pesanan)
    {
        $user = Auth::user();
        
        // 1. Validasi Multi-Tenant & Peran Pengelola
        if ($user->role !== 'pengelola') {
            abort(403, 'Akses Ditolak: Hanya Pengelola Komunitas yang bertugas di lapangan yang dapat menekan tombol Selesai Perjalanan.');
        }

        if ($pesanan->komunitas_id !== $user->komunitas_id) {
            abort(403, 'Akses Ditolak: Ini bukan pesanan komunitas Anda.');
        }

        // 2. Pastikan armada (Jeep) sudah dipilih
        if ($pesanan->armadas()->count() == 0) {
            return back()->with('error', 'Gagal: Anda belum menugaskan Armada Jeep untuk trip ini! Silakan pilih Jeep dan tekan Simpan Penugasan Armada terlebih dahulu.');
        }

        if ($pesanan->status !== 'DP Lunas') {
            return back()->with('error', 'Hanya pesanan berstatus "DP Lunas" yang dapat ditandai selesai perjalanan.');
        }

        $pesanan->update(['status' => 'Selesai Perjalanan']);

        return back()->with('success', 'Perjalanan berhasil ditandai selesai! Customer kini mendapat notifikasi untuk melakukan Pelunasan.');
    }

    public function destroy(Pesanan $pesanan)
    {
        $user = Auth::user();
        // Proteksi: Pengelola tidak diizinkan menghapus pesanan
        if ($user->role === 'pengelola') {
            abort(403, 'Akses Ditolak: Pengelola tidak diizinkan menghapus data pesanan.');
        }

        // Jangan izinkan penghapusan jika pesanan sudah dibayar atau selesai
        if (in_array($pesanan->status, ['DP Lunas', 'Selesai Perjalanan', 'Lunas', 'Selesai'])) {
            return back()->with('error', 'Pesanan yang sudah terbayar atau selesai perjalanannya tidak dapat dihapus.');
        }

        $pesanan->delete();
        return back()->with('success', 'Pesanan berhasil dihapus.');
    }
}