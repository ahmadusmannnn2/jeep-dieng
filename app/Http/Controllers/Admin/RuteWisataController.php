<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RuteWisata;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RuteWisataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = RuteWisata::query();

        // Filter data sesuai komunitas yang login
        if ($user->role === 'admin_komunitas') {
            $query->where('komunitas_id', $user->komunitas_id);
        }

        $ruteWisata = $query->latest()->get();
        return view('admin.rute_wisata.index', compact('ruteWisata'));
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
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable'
        ]);

        $data = $request->all();
        
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
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
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable'
        ]);

        $data = $request->all();
        
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        $ruteWisata->update($data);
        return redirect()->route('admin.rute-wisata.index')->with('success', 'Rute wisata berhasil diperbarui!');
    }

    public function destroy(RuteWisata $ruteWisata)
    {
        $ruteWisata->delete();
        return redirect()->route('admin.rute-wisata.index')->with('success', 'Rute wisata berhasil dihapus!');
    }
}