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

        // GEMBOK MULTI-TENANT
        if ($user->role === 'pengelola') {
            $query->where('komunitas_id', $user->komunitas_id);
        } else {
            if ($request->filled('komunitas_id')) {
                $query->where('komunitas_id', $request->komunitas_id);
            }
        }

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        $jadwal = $query->orderBy('tanggal', 'asc')->orderBy('jam', 'asc')->get();
        $komunitas = Komunitas::all();

        return view('admin.jadwal.index', compact('jadwal', 'komunitas'));
    }

    public function create()
    {
        $user = Auth::user();
        $komunitas = $user->role === 'pengelola' 
            ? Komunitas::where('id', $user->komunitas_id)->get() 
            : Komunitas::all();

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
        
        if (Auth::user()->role === 'pengelola') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        JadwalKeberangkatan::create($data);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal keberangkatan berhasil ditambahkan!');
    }

    public function edit(JadwalKeberangkatan $jadwal)
    {
        $user = Auth::user();
        if ($user->role === 'pengelola' && $jadwal->komunitas_id !== $user->komunitas_id) {
            abort(403, 'Akses Ditolak.');
        }

        $komunitas = $user->role === 'pengelola' 
            ? Komunitas::where('id', $user->komunitas_id)->get() 
            : Komunitas::all();

        return view('admin.jadwal.edit', compact('jadwal', 'komunitas'));
    }

    public function update(Request $request, JadwalKeberangkatan $jadwal)
    {
        if (Auth::user()->role === 'pengelola' && $jadwal->komunitas_id !== Auth::user()->komunitas_id) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
            'komunitas_id' => 'required|exists:komunitas,id'
        ]);

        $data = $request->except(['_token', '_method']);
        
        if (Auth::user()->role === 'pengelola') {
            $data['komunitas_id'] = Auth::user()->komunitas_id;
        }

        $jadwal->update($data);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal keberangkatan berhasil diperbarui!');
    }

    public function destroy(JadwalKeberangkatan $jadwal)
    {
        if (Auth::user()->role === 'pengelola' && $jadwal->komunitas_id !== Auth::user()->komunitas_id) {
            abort(403, 'Akses Ditolak.');
        }

        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal keberangkatan berhasil dihapus!');
    }
}