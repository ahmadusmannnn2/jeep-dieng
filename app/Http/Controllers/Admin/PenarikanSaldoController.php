<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PenarikanSaldo;
use App\Models\Pesanan;
use App\Models\Komunitas;
use App\Models\User;
use App\Notifications\PenarikanDiajukan;
use App\Notifications\PenarikanSelesai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PenarikanSaldoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'pengelola') {
            $komunitas_id = $user->komunitas_id;
            
            // Pesanan yang bisa ditarik
            $saldoTersedia = Pesanan::with(['paketWisata', 'pembayaran'])
                ->where('komunitas_id', $komunitas_id)
                ->whereIn('status', ['Lunas', 'Selesai'])
                ->whereNull('penarikan_id')
                ->latest()
                ->get();
                
            // Riwayat penarikan
            $riwayatPenarikan = PenarikanSaldo::with('pesanan')
                ->where('komunitas_id', $komunitas_id)
                ->latest()
                ->get();
                
            return view('admin.penarikan_saldo.pengelola_index', compact('saldoTersedia', 'riwayatPenarikan'));
        } else {
            // Admin melihat semua pengajuan penarikan
            $penarikan = PenarikanSaldo::with(['komunitas', 'pesanan'])
                ->latest()
                ->get();
                
            return view('admin.penarikan_saldo.admin_index', compact('penarikan'));
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'pengelola') {
            abort(403);
        }

        $request->validate([
            'pesanan_ids' => 'required|array',
            'pesanan_ids.*' => 'exists:pesanan,id'
        ]);

        $komunitas_id = $user->komunitas_id;

        DB::beginTransaction();
        try {
            // Ambil pesanan yang dipilih, pastikan milik komunitas ini dan belum ditarik
            $pesanans = Pesanan::whereIn('id', $request->pesanan_ids)
                ->where('komunitas_id', $komunitas_id)
                ->whereIn('status', ['Lunas', 'Selesai'])
                ->whereNull('penarikan_id')
                ->lockForUpdate()
                ->get();

            if ($pesanans->isEmpty()) {
                return back()->with('error', 'Tidak ada pesanan valid yang bisa ditarik.');
            }

            // Hitung total dari tabel pembayaran
            $totalPendapatan = \App\Models\Pembayaran::whereIn('pesanan_id', $pesanans->pluck('id'))
                ->where('status', 'Valid')
                ->sum('jumlah_bayar');

            $potonganAdmin = $totalPendapatan * 0.10;
            $totalDiterima = $totalPendapatan - $potonganAdmin;

            // Buat record Penarikan
            $penarikan = PenarikanSaldo::create([
                'komunitas_id' => $komunitas_id,
                'total_pendapatan' => $totalPendapatan,
                'potongan_admin' => $potonganAdmin,
                'total_diterima' => $totalDiterima,
                'status' => 'Diajukan',
            ]);

            // Update pesanan
            Pesanan::whereIn('id', $pesanans->pluck('id'))->update([
                'penarikan_id' => $penarikan->id
            ]);

            // Kirim notifikasi ke Admin
            $admins = User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new PenarikanDiajukan($penarikan));

            DB::commit();
            return back()->with('success', 'Penarikan dana berhasil diajukan. Menunggu proses dari Admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, PenarikanSaldo $penarikanSaldo)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
            
            $penarikanSaldo->update([
                'status' => 'Selesai',
                'bukti_transfer' => $path
            ]);

            // Notifikasi ke pengelola
            $pengelolas = User::where('role', 'pengelola')->where('komunitas_id', $penarikanSaldo->komunitas_id)->get();
            \Illuminate\Support\Facades\Notification::send($pengelolas, new PenarikanSelesai($penarikanSaldo));

            return back()->with('success', 'Penarikan berhasil diproses dan bukti transfer telah diunggah.');
        }

        return back()->with('error', 'Gagal mengunggah bukti transfer.');
    }
}
