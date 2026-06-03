<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supir;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupirController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Supir::query();

        if ($user->role === 'admin_komunitas') {
            $query->where('komunitas_id', $user->komunitas_id);
        }

        $supirs = $query->latest()->get();
        return view('admin.supir.index', compact('supirs'));
    }

    public function create()
    {
        // Jika super admin, berikan pilihan komunitas. Jika admin biasa, kosongkan.
        $komunitas = Auth::user()->role === 'super_admin' ? Komunitas::all() : null;
        return view('admin.supir.create', compact('komunitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supir' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'status' => 'required|in:Tersedia,Sedang Bertugas,Tidak Aktif',
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable'
        ]);

        $data = $request->all();
        
        // Otomatis assign komunitas jika yang login adalah admin komunitas
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        Supir::create($data);
        return redirect()->route('admin.supir.index')->with('success', 'Data supir berhasil ditambahkan!');
    }

    public function edit(Supir $supir)
    {
        $komunitas = Auth::user()->role === 'super_admin' ? Komunitas::all() : null;
        return view('admin.supir.edit', compact('supir', 'komunitas'));
    }

    public function update(Request $request, Supir $supir)
    {
        $request->validate([
            'nama_supir' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'status' => 'required|in:Tersedia,Sedang Bertugas,Tidak Aktif',
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable'
        ]);

        $data = $request->all();
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        $supir->update($data);
        return redirect()->route('admin.supir.index')->with('success', 'Data supir berhasil diperbarui!');
    }

    public function destroy(Supir $supir)
    {
        $supir->delete();
        return redirect()->route('admin.supir.index')->with('success', 'Data supir berhasil dihapus!');
    }
}