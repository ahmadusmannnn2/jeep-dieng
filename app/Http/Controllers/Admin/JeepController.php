<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jeep;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JeepController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Jeep::query();

        if ($request->filled('komunitas_id')) {
            $query->where('komunitas_id', $request->komunitas_id);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_jeep', 'like', "%{$search}%")
                  ->orWhere('nomor_polisi', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jeeps = $query->latest()->get();
        $komunitas = Komunitas::all();

        return view('admin.jeep.index', compact('jeeps', 'komunitas'));
    }

    public function create()
    {
        $komunitas = Komunitas::all();
        return view('admin.jeep.create', compact('komunitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jeep' => 'required|string|max:255',
            'nomor_polisi' => 'required|string|max:20|unique:jeep,nomor_polisi',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:Tersedia,Disewa,Perbaikan',
            'komunitas_id' => 'required|exists:komunitas,id'
        ]);

        $data = $request->all();
        


        Jeep::create($data);
        return redirect()->route('admin.jeep.index')->with('success', 'Armada Jeep berhasil ditambahkan!');
    }

    public function edit(Jeep $jeep)
    {
        $komunitas = Komunitas::all();
        return view('admin.jeep.edit', compact('jeep', 'komunitas'));
    }

    public function update(Request $request, Jeep $jeep)
    {
        $request->validate([
            'nama_jeep' => 'required|string|max:255',
            'nomor_polisi' => 'required|string|max:20|unique:jeep,nomor_polisi,' . $jeep->id,
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:Tersedia,Disewa,Perbaikan',
            'komunitas_id' => 'required|exists:komunitas,id'
        ]);

        $data = $request->all();


        $jeep->update($data);
        return redirect()->route('admin.jeep.index')->with('success', 'Data Jeep berhasil diperbarui!');
    }

    public function destroy(Jeep $jeep)
    {
        $jeep->delete();
        return redirect()->route('admin.jeep.index')->with('success', 'Armada Jeep berhasil dihapus!');
    }
}