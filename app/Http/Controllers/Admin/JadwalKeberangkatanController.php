<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKeberangkatan;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKeberangkatanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = JadwalKeberangkatan::query();

        if ($request->filled('komunitas_id')) {
            $query->where('komunitas_id', $request->komunitas_id);
        }

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        // Urutkan berdasarkan tanggal terdekat
        $jadwal = $query->orderBy('tanggal', 'asc')->orderBy('jam', 'asc')->get();
        $komunitas = Komunitas::all();

        return view('admin.jadwal.index', compact('jadwal', 'komunitas'));
    }

    public function create()
    {
        $komunitas = Komunitas::all();
        return view('admin.jadwal.create', compact('komunitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
            'komunitas_id' => 'required|exists:komunitas,id'
        ]);

        $data = $request->except(['_token', '_method']);
        


        JadwalKeberangkatan::create($data);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal keberangkatan berhasil ditambahkan!');
    }

    public function edit(JadwalKeberangkatan $jadwal)
    {
        $komunitas = Komunitas::all();
        return view('admin.jadwal.edit', compact('jadwal', 'komunitas'));
    }

    public function update(Request $request, JadwalKeberangkatan $jadwal)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
            'komunitas_id' => 'required|exists:komunitas,id'
        ]);

        $data = $request->except(['_token', '_method']);
        


        $jadwal->update($data);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal keberangkatan berhasil diperbarui!');
    }

    public function destroy(JadwalKeberangkatan $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal keberangkatan berhasil dihapus!');
    }
}