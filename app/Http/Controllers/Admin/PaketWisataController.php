<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaketWisataController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PaketWisata::query();

        // GEMBOK MULTI-TENANT
        if ($user->role === 'pengelola') {
            $query->where('komunitas_id', $user->komunitas_id);
        } else {
            if ($request->filled('komunitas_id')) {
                $query->where('komunitas_id', $request->komunitas_id);
            }
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
        $user = Auth::user();
        $komunitas = $user->role === 'pengelola' 
            ? Komunitas::where('id', $user->komunitas_id)->get() 
            : Komunitas::all();

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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $data = $request->except('gambar'); 

        if (Auth::user()->role === 'pengelola') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('paket_wisata', 'public');
        }

        PaketWisata::create($data);
        return redirect()->route('admin.paket-wisata.index')->with('success', 'Paket wisata berhasil ditambahkan!');
    }

    public function edit(PaketWisata $paketWisata)
    {
        $user = Auth::user();
        if ($user->role === 'pengelola' && $paketWisata->komunitas_id !== $user->komunitas_id) {
            abort(403, 'Akses Ditolak.');
        }

        $komunitas = $user->role === 'pengelola' 
            ? Komunitas::where('id', $user->komunitas_id)->get() 
            : Komunitas::all();

        return view('admin.paket_wisata.edit', compact('paketWisata', 'komunitas'));
    }

    public function update(Request $request, PaketWisata $paketWisata)
    {
        if (Auth::user()->role === 'pengelola' && $paketWisata->komunitas_id !== Auth::user()->komunitas_id) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'komunitas_id' => 'required|exists:komunitas,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $data = $request->except('gambar');

        if (Auth::user()->role === 'pengelola') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        if ($request->hasFile('gambar')) {
            if ($paketWisata->gambar && Storage::disk('public')->exists($paketWisata->gambar)) {
                Storage::disk('public')->delete($paketWisata->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('paket_wisata', 'public');
        }

        $paketWisata->update($data);
        return redirect()->route('admin.paket-wisata.index')->with('success', 'Paket wisata berhasil diperbarui!');
    }

    public function destroy(PaketWisata $paketWisata)
    {
        if (Auth::user()->role === 'pengelola' && $paketWisata->komunitas_id !== Auth::user()->komunitas_id) {
            abort(403, 'Akses Ditolak.');
        }

        if ($paketWisata->gambar && Storage::disk('public')->exists($paketWisata->gambar)) {
            Storage::disk('public')->delete($paketWisata->gambar);
        }
        
        $paketWisata->delete();
        return redirect()->route('admin.paket-wisata.index')->with('success', 'Paket wisata berhasil dihapus!');
    }
}