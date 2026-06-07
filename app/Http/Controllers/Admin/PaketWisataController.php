<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan baris ini

class PaketWisataController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PaketWisata::query();

        if ($request->filled('komunitas_id')) {
            $query->where('komunitas_id', $request->komunitas_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_paket', 'like', "%{$search}%");
        }

        $paketWisata = $query->latest()->get();
        $komunitas = Komunitas::all();

        return view('admin.paket_wisata.index', compact('paketWisata', 'komunitas'));
    }

    public function create()
    {
        $komunitas = Komunitas::all();
        return view('admin.paket_wisata.create', compact('komunitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'komunitas_id' => 'required|exists:komunitas,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072' // Validasi untuk gambar
        ]);

        $data = $request->except('gambar'); // Pisahkan gambar dari array data umum
        


        // Proses unggah gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('paket_wisata', 'public');
        }

        PaketWisata::create($data);
        return redirect()->route('admin.paket-wisata.index')->with('success', 'Paket wisata berhasil ditambahkan!');
    }

    public function edit(PaketWisata $paketWisata)
    {
        $komunitas = Komunitas::all();
        return view('admin.paket_wisata.edit', compact('paketWisata', 'komunitas'));
    }

    public function update(Request $request, PaketWisata $paketWisata)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'komunitas_id' => 'required|exists:komunitas,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072' // Validasi untuk gambar
        ]);

        $data = $request->except('gambar');
        


        // Proses update gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada, agar server tidak penuh
            if ($paketWisata->gambar && Storage::disk('public')->exists($paketWisata->gambar)) {
                Storage::disk('public')->delete($paketWisata->gambar);
            }
            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('paket_wisata', 'public');
        }

        $paketWisata->update($data);
        return redirect()->route('admin.paket-wisata.index')->with('success', 'Paket wisata berhasil diperbarui!');
    }

    public function destroy(PaketWisata $paketWisata)
    {
        // Hapus file gambar dari server sebelum menghapus data di database
        if ($paketWisata->gambar && Storage::disk('public')->exists($paketWisata->gambar)) {
            Storage::disk('public')->delete($paketWisata->gambar);
        }
        
        $paketWisata->delete();
        return redirect()->route('admin.paket-wisata.index')->with('success', 'Paket wisata berhasil dihapus!');
    }
}