<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komunitas extends Model
{
    use HasFactory;

    protected $table = 'komunitas';
    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class, 'komunitas_id');
    }

    public function supir()
    {
        return $this->hasMany(Supir::class, 'komunitas_id');
    }

    public function jeep()
    {
        return $this->hasMany(Jeep::class, 'komunitas_id');
    }

    public function paketWisata()
    {
        return $this->hasMany(PaketWisata::class, 'komunitas_id');
    }

    public function ruteWisata()
    {
        return $this->hasMany(RuteWisata::class, 'komunitas_id');
    }

    public function jadwalKeberangkatan()
    {
        return $this->hasMany(JadwalKeberangkatan::class, 'komunitas_id');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'komunitas_id');
    }

    public function penarikanSaldo()
    {
        return $this->hasMany(PenarikanSaldo::class, 'komunitas_id');
    }

    public function testimonis()
    {
        // Komunitas punya banyak Testimoni melalui Pesanan
        return $this->hasManyThrough(Testimoni::class, Pesanan::class, 'komunitas_id', 'pesanan_id', 'id', 'id')
                    ->where('testimonis.is_tampil', true);
    }

    public function getAverageRatingAttribute()
    {
        // Gunakan koleksi yang sudah di-eager-load jika ada, 
        // agar tidak menjalankan query tambahan setiap kali dipanggil di view.
        if ($this->relationLoaded('testimonis')) {
            return $this->testimonis->avg('rating') ?: 0;
        }
        return $this->testimonis()->avg('rating') ?: 0;
    }

    public function getReviewCountAttribute()
    {
        if ($this->relationLoaded('testimonis')) {
            return $this->testimonis->count();
        }
        return $this->testimonis()->count();
    }
}