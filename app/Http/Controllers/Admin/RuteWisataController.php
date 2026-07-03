<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RuteWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RuteWisataController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin', except: ['index']),
        ];
    }

    public function index(Request $request)
    {
        $query = RuteWisata::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_rute', 'like', "%{$search}%");
        }

        $ruteWisata = $query->latest()->get();

        return view('admin.rute_wisata.index', compact('ruteWisata'));
    }

    public function create()
    {
        return view('admin.rute_wisata.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rute' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $data = $request->except('gambar');
        


        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('rute_wisata', 'public');
        }

        RuteWisata::create($data);
        return redirect()->route('admin.rute-wisata.index')->with('success', 'Rute wisata berhasil ditambahkan!');
    }

    public function edit(RuteWisata $ruteWisata)
    {
        return view('admin.rute_wisata.edit', compact('ruteWisata'));
    }

    public function update(Request $request, RuteWisata $ruteWisata)
    {
        $request->validate([
            'nama_rute' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $data = $request->except('gambar');
        


        if ($request->hasFile('gambar')) {
            if ($ruteWisata->gambar && Storage::disk('public')->exists($ruteWisata->gambar)) {
                Storage::disk('public')->delete($ruteWisata->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('rute_wisata', 'public');
        }

        $ruteWisata->update($data);
        return redirect()->route('admin.rute-wisata.index')->with('success', 'Rute wisata berhasil diperbarui!');
    }

    public function destroy(RuteWisata $ruteWisata)
    {

        if ($ruteWisata->gambar && Storage::disk('public')->exists($ruteWisata->gambar)) {
            Storage::disk('public')->delete($ruteWisata->gambar);
        }
        
        $ruteWisata->delete();
        return redirect()->route('admin.rute-wisata.index')->with('success', 'Rute wisata berhasil dihapus!');
    }
}