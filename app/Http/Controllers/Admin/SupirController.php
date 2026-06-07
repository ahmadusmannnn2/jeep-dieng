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

        if ($request->filled('komunitas_id')) {
            $query->where('komunitas_id', $request->komunitas_id);
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
        $komunitas = Komunitas::all();
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
        


        Supir::create($data);
        return redirect()->route('admin.supir.index')->with('success', 'Data supir berhasil ditambahkan!');
    }

    public function edit(Supir $supir)
    {
        $komunitas = Komunitas::all();
        return view('admin.supir.edit', compact('supir', 'komunitas'));
    }

    public function update(Request $request, Supir $supir)
    {
        $request->validate([
            'nama_supir' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'status' => 'required|in:Tersedia,Sedang Bertugas,Tidak Aktif',
            'komunitas_id' => 'required|exists:komunitas,id'
        ]);

        $data = $request->except(['_token', '_method']);


        $supir->update($data);
        return redirect()->route('admin.supir.index')->with('success', 'Data supir berhasil diperbarui!');
    }

    public function destroy(Supir $supir)
    {
        $supir->delete();
        return redirect()->route('admin.supir.index')->with('success', 'Data supir berhasil dihapus!');
    }
}