<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komunitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

        // Hitung armada, supir, dan load akun pengelola
        $komunitas = $query->with('users')->withCount(['jeep', 'supir'])->latest()->get();

        return view('admin.komunitas.index', compact('komunitas'));
    }

    public function create()
    {
        return view('admin.komunitas.create');
    }

    public function store(Request $request)
    {
        // Validasi Komunitas dan Akun Pengelola
        $request->validate([
            'nama_komunitas' => 'required|string|max:255',
            'ketua' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // 1. Simpan Data Komunitas
        $komunitas = Komunitas::create($request->only(['nama_komunitas', 'ketua', 'no_hp', 'alamat']));

        // 2. Buatkan Akun User Pengelola
        User::create([
            'name' => $request->ketua ?? 'Pengelola ' . $komunitas->nama_komunitas,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pengelola',
            'komunitas_id' => $komunitas->id,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.komunitas.index')->with('success', 'Komunitas dan Akun Pengelola berhasil ditambahkan!');
    }

    public function edit(Komunitas $komunitas)
    {
        // Ambil akun pengelola pertama yang terkait dengan komunitas ini
        $pengelola = $komunitas->users()->where('role', 'pengelola')->first();
        return view('admin.komunitas.edit', compact('komunitas', 'pengelola'));
    }

    public function update(Request $request, Komunitas $komunitas)
    {
        $pengelola = $komunitas->users()->where('role', 'pengelola')->first();

        // Validasi
        $request->validate([
            'nama_komunitas' => 'required|string|max:255',
            'ketua' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($pengelola ? $pengelola->id : null)
            ],
            'password' => 'nullable|string|min:8', // Password opsional saat update
        ]);

        // 1. Update Data Komunitas
        $komunitas->update($request->only(['nama_komunitas', 'ketua', 'no_hp', 'alamat']));

        // 2. Update atau Buat Akun Pengelola
        if ($pengelola) {
            $pengelola->email = $request->email;
            $pengelola->name = $request->ketua ?? $pengelola->name;
            $pengelola->no_hp = $request->no_hp ?? $pengelola->no_hp;
            
            if ($request->filled('password')) {
                $pengelola->password = Hash::make($request->password);
            }
            $pengelola->save();
        } else {
            // Jika sebelumnya belum ada akun, pastikan password diisi sebelum buat baru
            if (!$request->filled('password')) {
                return back()->withErrors(['password' => 'Password wajib diisi untuk membuat akun pengelola baru.'])->withInput();
            }
            User::create([
                'name'         => $request->ketua ?? 'Pengelola ' . $komunitas->nama_komunitas,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'role'         => 'pengelola',
                'komunitas_id' => $komunitas->id,
                'no_hp'        => $request->no_hp,
            ]);
        }

        return redirect()->route('admin.komunitas.index')->with('success', 'Data Komunitas dan Akun Pengelola berhasil diperbarui!');
    }

    public function destroy(Komunitas $komunitas)
    {
        // Hapus juga akun pengelola yang tertaut agar tidak menjadi akun yatim (orphan)
        $komunitas->users()->delete();
        
        $komunitas->delete();
        return redirect()->route('admin.komunitas.index')->with('success', 'Data Komunitas beserta akun pengelolanya berhasil dihapus!');
    }
}