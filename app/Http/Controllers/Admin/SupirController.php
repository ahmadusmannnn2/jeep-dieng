<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supir;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupirController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Supir::query();

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
            $query->where(function($q) use ($search) {
                $q->where('nama_supir', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $supirs = $query->latest()->get();
        $komunitas = Komunitas::all();

        return view('admin.supir.index', compact('supirs', 'komunitas'));
    }

    public function create()
    {
        $user = Auth::user();
        // Pengelola hanya melihat komunitasnya sendiri di dropdown
        $komunitas = $user->role === 'pengelola' 
            ? Komunitas::where('id', $user->komunitas_id)->get() 
            : Komunitas::all();

        return view('admin.supir.create', compact('komunitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supir' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'status' => 'required|in:Tersedia,Sedang Bertugas,Tidak Aktif',
            'komunitas_id' => 'required|exists:komunitas,id'
        ]);

        $data = $request->except(['_token', '_method']);
        
        // Proteksi: Paksa komunitas_id menjadi milik pengelola
        if (Auth::user()->role === 'pengelola') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        Supir::create($data);
        return redirect()->route('admin.supir.index')->with('success', 'Data supir berhasil ditambahkan!');
    }

    public function edit(Supir $supir)
    {
        $user = Auth::user();
        if ($user->role === 'pengelola' && $supir->komunitas_id !== $user->komunitas_id) {
            abort(403, 'Akses Ditolak.');
        }

        $komunitas = $user->role === 'pengelola' 
            ? Komunitas::where('id', $user->komunitas_id)->get() 
            : Komunitas::all();

        return view('admin.supir.edit', compact('supir', 'komunitas'));
    }

    public function update(Request $request, Supir $supir)
    {
        if (Auth::user()->role === 'pengelola' && $supir->komunitas_id !== Auth::user()->komunitas_id) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'nama_supir' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'status' => 'required|in:Tersedia,Sedang Bertugas,Tidak Aktif',
            'komunitas_id' => 'required|exists:komunitas,id'
        ]);

        $data = $request->except(['_token', '_method']);
        
        if (Auth::user()->role === 'pengelola') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        $supir->update($data);
        return redirect()->route('admin.supir.index')->with('success', 'Data supir berhasil diperbarui!');
    }

    public function destroy(Supir $supir)
    {
        if (Auth::user()->role === 'pengelola' && $supir->komunitas_id !== Auth::user()->komunitas_id) {
            abort(403, 'Akses Ditolak.');
        }

        $supir->delete();
        return redirect()->route('admin.supir.index')->with('success', 'Data supir berhasil dihapus!');
    }
}