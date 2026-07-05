<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimoni::query()->with('pesanan.komunitas');

        if (\Illuminate\Support\Facades\Auth::user()->role === 'pengelola') {
            $query->whereHas('pesanan', function($q) {
                $q->where('komunitas_id', \Illuminate\Support\Facades\Auth::user()->komunitas_id);
            });
        }

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
        if (\Illuminate\Support\Facades\Auth::user()->role === 'pengelola') abort(403);
        return view('admin.testimoni.create');
    }

    public function store(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role === 'pengelola') abort(403);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'asal_kota' => 'nullable|string|max:255',
            'pesan' => 'required|string',
            'is_tampil' => 'required|boolean',
        ]);

        Testimoni::create($validated);

        return redirect()->route('admin.testimoni.index')->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function edit(Testimoni $testimoni)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role === 'pengelola') abort(403);
        return view('admin.testimoni.edit', compact('testimoni'));
    }

    public function update(Request $request, Testimoni $testimoni)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role === 'pengelola') abort(403);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'asal_kota' => 'nullable|string|max:255',
            'pesan' => 'required|string',
            'is_tampil' => 'required|boolean',
        ]);

        $testimoni->update($validated);

        return redirect()->route('admin.testimoni.index')->with('success', 'Testimoni berhasil diperbarui!');
    }

    public function destroy(Testimoni $testimoni)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role === 'pengelola') abort(403);
        $testimoni->delete();
        return redirect()->route('admin.testimoni.index')->with('success', 'Testimoni berhasil dihapus!');
    }

    public function toggle(Testimoni $testimoni)
    {
        if (\Illuminate\Support\Facades\Auth::user()->role === 'pengelola') abort(403);
        $testimoni->update(['is_tampil' => !$testimoni->is_tampil]);
        return redirect()->route('admin.testimoni.index')->with('success', 'Status testimoni berhasil diubah!');
    }
}
