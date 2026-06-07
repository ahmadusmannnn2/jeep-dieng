<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komunitas;
use Illuminate\Http\Request;

class KomunitasController extends Controller
{
    public function index(Request $request)
    {
        $query = Komunitas::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_komunitas', 'like', "%{$search}%")
                  ->orWhere('ketua', 'like', "%{$search}%");
            });
        }

        // Hitung armada dan supir yang dimiliki komunitas ini
        $komunitas = $query->withCount(['jeep', 'supir'])->latest()->get();

        return view('admin.komunitas.index', compact('komunitas'));
    }

    public function create()
    {
        return view('admin.komunitas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_komunitas' => 'required|string|max:255',
            'ketua' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string'
        ]);

        Komunitas::create($request->all());

        return redirect()->route('admin.komunitas.index')->with('success', 'Data Komunitas berhasil ditambahkan!');
    }

    public function edit(Komunitas $komunitas)
    {
        return view('admin.komunitas.edit', compact('komunitas'));
    }

    public function update(Request $request, Komunitas $komunitas)
    {
        $request->validate([
            'nama_komunitas' => 'required|string|max:255',
            'ketua' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string'
        ]);

        $komunitas->update($request->all());

        return redirect()->route('admin.komunitas.index')->with('success', 'Data Komunitas berhasil diperbarui!');
    }

    public function destroy(Komunitas $komunitas)
    {
        $komunitas->delete();
        return redirect()->route('admin.komunitas.index')->with('success', 'Data Komunitas berhasil dihapus!');
    }
}
