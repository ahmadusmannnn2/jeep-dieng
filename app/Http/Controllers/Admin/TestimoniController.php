<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimoni::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('asal_kota', 'like', "%{$search}%")
                  ->orWhere('pesan', 'like', "%{$search}%");
            });
        }

        $testimonis = $query->latest()->get();
        return view('admin.testimoni.index', compact('testimonis'));
    }

    public function create()
    {
        return view('admin.testimoni.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'asal_kota' => 'nullable|string|max:255',
            'pesan' => 'required|string',
            'is_tampil' => 'required|boolean',
        ]);

        Testimoni::create($request->all());

        return redirect()->route('admin.testimoni.index')->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function edit(Testimoni $testimoni)
    {
        return view('admin.testimoni.edit', compact('testimoni'));
    }

    public function update(Request $request, Testimoni $testimoni)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'asal_kota' => 'nullable|string|max:255',
            'pesan' => 'required|string',
            'is_tampil' => 'required|boolean',
        ]);

        $testimoni->update($request->all());

        return redirect()->route('admin.testimoni.index')->with('success', 'Testimoni berhasil diperbarui!');
    }

    public function destroy(Testimoni $testimoni)
    {
        $testimoni->delete();
        return redirect()->route('admin.testimoni.index')->with('success', 'Testimoni berhasil dihapus!');
    }

    public function toggle(Testimoni $testimoni)
    {
        $testimoni->update(['is_tampil' => !$testimoni->is_tampil]);
        return redirect()->route('admin.testimoni.index')->with('success', 'Status testimoni berhasil diubah!');
    }
}
