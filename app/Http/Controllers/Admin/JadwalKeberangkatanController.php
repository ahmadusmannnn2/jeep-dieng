<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKeberangkatan;
use App\Models\Komunitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKeberangkatanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = JadwalKeberangkatan::query();

        // Filter data sesuai komunitas yang login
        if ($user->role === 'admin_komunitas') {
            $query->where('komunitas_id', $user->komunitas_id);
        }

        // Urutkan berdasarkan tanggal terdekat
        $jadwal = $query->orderBy('tanggal', 'asc')->orderBy('jam', 'asc')->get();
        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $komunitas = Auth::user()->role === 'super_admin' ? Komunitas::all() : null;
        return view('admin.jadwal.create', compact('komunitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable'
        ]);

        $data = $request->all();
        
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        JadwalKeberangkatan::create($data);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal keberangkatan berhasil ditambahkan!');
    }

    public function edit(JadwalKeberangkatan $jadwal)
    {
        $komunitas = Auth::user()->role === 'super_admin' ? Komunitas::all() : null;
        return view('admin.jadwal.edit', compact('jadwal', 'komunitas'));
    }

    public function update(Request $request, JadwalKeberangkatan $jadwal)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
            'komunitas_id' => Auth::user()->role === 'super_admin' ? 'required|exists:komunitas,id' : 'nullable'
        ]);

        $data = $request->all();
        
        if (Auth::user()->role === 'admin_komunitas') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        $jadwal->update($data);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal keberangkatan berhasil diperbarui!');
    }

    public function destroy(JadwalKeberangkatan $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal keberangkatan berhasil dihapus!');
    }
}