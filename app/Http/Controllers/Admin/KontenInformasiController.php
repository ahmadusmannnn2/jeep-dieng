<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontenInformasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KontenInformasiController extends Controller
{
    public function index(Request $request)
    {
        $query = KontenInformasi::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        $konten = $query->latest()->get();
        return view('admin.konten.index', compact('konten'));
    }

    public function create()
    {
        return view('admin.konten.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_publish' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // Maksimal 2MB
        ]);

        $data = $request->except(['_token', '_method', 'gambar']);

        // Logika Upload Gambar
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('promo', 'public');
        }

        KontenInformasi::create($data);
        return redirect()->route('admin.konten-informasi.index')->with('success', 'Konten informasi berhasil diterbitkan!');
    }

    public function edit(KontenInformasi $kontenInformasi)
    {
        return view('admin.konten.edit', compact('kontenInformasi'));
    }

    public function update(Request $request, KontenInformasi $kontenInformasi)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_publish' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->except(['_token', '_method', 'gambar']);

        // Logika Ganti Gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($kontenInformasi->gambar && Storage::disk('public')->exists($kontenInformasi->gambar)) {
                Storage::disk('public')->delete($kontenInformasi->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('promo', 'public');
        }

        $kontenInformasi->update($data);
        return redirect()->route('admin.konten-informasi.index')->with('success', 'Konten informasi berhasil diperbarui!');
    }

    public function destroy(KontenInformasi $kontenInformasi)
    {
        // Hapus file gambar fisik
        if ($kontenInformasi->gambar && Storage::disk('public')->exists($kontenInformasi->gambar)) {
            Storage::disk('public')->delete($kontenInformasi->gambar);
        }
        
        $kontenInformasi->delete();
        return redirect()->route('admin.konten-informasi.index')->with('success', 'Konten informasi berhasil dihapus!');
    }
}