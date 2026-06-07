<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function komunitas()
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }

    public function paketWisata()
    {
        return $this->belongsTo(PaketWisata::class, 'paket_wisata_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalKeberangkatan::class, 'jadwal_id');
    }

    public function jeep()
    {
        return $this->belongsTo(Jeep::class, 'jeep_id');
    }

    public function supir()
    {
        return $this->belongsTo(Supir::class, 'supir_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'pesanan_id');
    }

    public function pembayaran()
    {
        // Helper untuk mendapatkan pembayaran terbaru (bisa DP atau Pelunasan)
        return $this->hasOne(Pembayaran::class, 'pesanan_id')->latestOfMany();
    }
}