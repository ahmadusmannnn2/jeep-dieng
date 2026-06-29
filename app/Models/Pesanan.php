<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'pesanan';

    // Mengizinkan semua kolom untuk diisi (Mass Assignment), kecuali kolom 'id'
    // Kolom baru seperti 'tipe_trip', 'jumlah_jeep', 'titik_jemput' otomatis langsung bisa disimpan.
    protected $guarded = ['id'];

    // Relasi ke User (Customer yang memesan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Komunitas Penyelenggara
    public function komunitas()
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }

    // Relasi ke Paket Wisata yang dipilih
    public function paketWisata()
    {
        return $this->belongsTo(PaketWisata::class, 'paket_wisata_id');
    }

    // Relasi ke Jadwal (Jika masih menggunakan sistem jadwal ID)
    public function jadwal()
    {
        return $this->belongsTo(JadwalKeberangkatan::class, 'jadwal_id');
    }

    // Catatan: Relasi langsung ke jeep/supir dihapus karena kolom jeep_id & supir_id
    // sudah dipindahkan ke tabel pivot 'pesanan_armadas' (migrasi 28 Jun 2026).
    // Gunakan relasi armadas() -> armadas.jeep / armadas.supir sebagai gantinya.

    // Relasi HasMany: Satu pesanan bisa memiliki banyak riwayat pembayaran (Misal: DP lalu Pelunasan)
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'pesanan_id');
    }

    // Relasi HasOne: Helper khusus untuk langsung mengambil data pembayaran yang paling terakhir / terbaru
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id')->latestOfMany();
    }

    // Relasi HasMany ke tabel pesanan_armadas
    public function armadas()
    {
        return $this->hasMany(PesananArmada::class, 'pesanan_id');
    }
}