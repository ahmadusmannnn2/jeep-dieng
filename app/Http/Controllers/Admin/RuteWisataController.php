<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RuteWisata;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RuteWisataController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = RuteWisata::with('komunitas');

        if ($user->role === 'admin_komunitas') {
            $query->where('komunitas_id', $user->komunitas_id);
        } else {
            if ($request->filled('komunitas_id')) {
                $query->where('komunitas_id', $request->komunitas_id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_rute', 'like', "%{$search}%");
        }

        $ruteWisata = $query->latest()->get();
        $komunitas = $user->role === 'super_admin' ? Komunitas::all() : collect();

        return view('admin.rute_wisata.index', compact('ruteWisata', 'komunitas'));
    }

    public function create()
    {
        $komunitas = Auth::user()->role === 'super_admin' ? Komunitas::all() : null;
        return view('admin.rute_wisata.create', compact('komunitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rute' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $data = $request->except('gambar');
        
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('rute_wisata', 'public');
        }

        RuteWisata::create($data);
        return redirect()->route('admin.rute-wisata.index')->with('success', 'Rute wisata berhasil ditambahkan!');
    }

    public function edit(RuteWisata $ruteWisata)
    {
        $komunitas = Auth::user()->role === 'super_admin' ? Komunitas::all() : null;
        return view('admin.rute_wisata.edit', compact('ruteWisata', 'komunitas'));
    }

    public function update(Request $request, RuteWisata $ruteWisata)
    {
        $request->validate([
            'nama_rute' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $data = $request->except('gambar');
        
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

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