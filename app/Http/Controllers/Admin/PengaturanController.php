<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        // Pastikan hanya Super Admin yang bisa mengakses halaman ini
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Akses ditolak.');
        }

        // Ambil data pertama. Jika belum ada di database, buat otomatis (firstOrCreate).
        $pengaturan = Pengaturan::firstOrCreate(['id' => 1], [
            'nama_website' => 'JEEP DIENG',
            'no_telp' => '0812-3456-7890',
            'email' => 'support@jeepdieng.com',
            'alamat' => 'Wonosobo, Jawa Tengah',
            'deskripsi_footer' => 'Platform penyewaan Jeep resmi dan terpercaya di Dataran Tinggi Dieng. Menghubungkan Anda dengan komunitas Jeep lokal untuk pengalaman wisata tak terlupakan.'
        ]);

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        $request->validate([
            'nama_website' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'no_telp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'deskripsi_footer' => 'nullable|string',
        ]);

        $pengaturan = Pengaturan::first();
        $data = $request->all();

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($pengaturan->logo && Storage::disk('public')->exists($pengaturan->logo)) {
                Storage::disk('public')->delete($pengaturan->logo);
            }
            $data['logo'] = $request->file('logo')->store('pengaturan', 'public');
        }

        $pengaturan->update($data);

        return redirect()->back()->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}