<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::firstOrCreate(['id' => 1], [
            'nama_website' => 'JEEP DIENG',
            'no_telp' => '0812-3456-7890',
            'email' => 'support@jeepdieng.com',
            'alamat' => 'Wonosobo, Jawa Tengah',
            'deskripsi_footer' => 'Platform penyewaan Jeep resmi dan terpercaya di Dataran Tinggi Dieng. Menghubungkan Anda dengan komunitas Jeep lokal untuk pengalaman wisata tak terlupakan.',
            'hero_badge' => 'Lebih dari 3 Komunitas Bergabung',
            'hero_title' => 'Jelajahi',
            'hero_title_highlight' => 'Keindahan Dieng',
            'hero_subtitle' => 'Pesan layanan Jeep tangguh untuk menaklukkan medan Dieng. Nikmati Golden Sunrise Sikunir dan Kawah Sikidang dengan aman dan nyaman bersama supir profesional kami.'
        ]);

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_website' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'no_telp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
            'deskripsi_footer' => 'nullable|string',
            'hero_badge' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_title_highlight' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'hero_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072' // Validasi Galeri
        ]);

        $pengaturan = Pengaturan::first();
        // Kecualikan input file gambar, kita proses terpisah
        $data = $request->except(['hero_images', 'gallery_images']);

        if ($request->hasFile('logo')) {
            if ($pengaturan->logo && Storage::disk('public')->exists($pengaturan->logo)) {
                Storage::disk('public')->delete($pengaturan->logo);
            }
            $data['logo'] = $request->file('logo')->store('pengaturan', 'public');
        }

        if ($request->hasFile('hero_images')) {
            if ($pengaturan->hero_images) {
                foreach($pengaturan->hero_images as $oldImage) {
                    if(Storage::disk('public')->exists($oldImage)) { Storage::disk('public')->delete($oldImage); }
                }
            }
            $imagesPath = [];
            foreach($request->file('hero_images') as $file) {
                $imagesPath[] = $file->store('hero', 'public');
            }
            $data['hero_images'] = $imagesPath;
        }

        // Proses Multiple Gambar Galeri
        if ($request->hasFile('gallery_images')) {
            if ($pengaturan->gallery_images) {
                foreach($pengaturan->gallery_images as $oldGalleryImage) {
                    if(Storage::disk('public')->exists($oldGalleryImage)) { Storage::disk('public')->delete($oldGalleryImage); }
                }
            }
            $galleryPath = [];
            foreach($request->file('gallery_images') as $file) {
                $galleryPath[] = $file->store('gallery', 'public');
            }
            $data['gallery_images'] = $galleryPath;
        }

        $pengaturan->update($data);

        return redirect()->back()->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}