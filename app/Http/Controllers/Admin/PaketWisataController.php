<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaketWisataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = PaketWisata::query();

        // Filter data sesuai komunitas yang login
        if ($user->role === 'admin_komunitas') {
            $query->where('komunitas_id', $user->komunitas_id);
        }

        $paketWisata = $query->latest()->get();
        return view('admin.paket_wisata.index', compact('paketWisata'));
    }

    public function create()
    {
        $komunitas = Auth::user()->role === 'super_admin' ? Komunitas::all() : null;
        return view('admin.paket_wisata.create', compact('komunitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable'
        ]);

        $data = $request->all();
        
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        PaketWisata::create($data);
        return redirect()->route('admin.paket-wisata.index')->with('success', 'Paket wisata berhasil ditambahkan!');
    }

    public function edit(PaketWisata $paketWisata)
    {
        $komunitas = Auth::user()->role === 'super_admin' ? Komunitas::all() : null;
        return view('admin.paket_wisata.edit', compact('paketWisata', 'komunitas'));
    }

    public function update(Request $request, PaketWisata $paketWisata)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable'
        ]);

        $data = $request->all();
        
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        $paketWisata->update($data);
        return redirect()->route('admin.paket-wisata.index')->with('success', 'Paket wisata berhasil diperbarui!');
    }

    public function destroy(PaketWisata $paketWisata)
    {
        $paketWisata->delete();
        return redirect()->route('admin.paket-wisata.index')->with('success', 'Paket wisata berhasil dihapus!');
    }
}