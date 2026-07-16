<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketWisata extends Model
{
    use HasFactory;

    protected $table = 'paket_wisata';
    protected $guarded = ['id']; // Menggunakan guarded agar kolom 'gambar' otomatis diizinkan masuk

    protected $casts = [
        'galeri' => 'array',
    ];

    public function komunitas()
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'paket_wisata_id');
    }

    public function rutes()
    {
        return $this->belongsToMany(RuteWisata::class, 'paket_rute', 'paket_wisata_id', 'rute_wisata_id')
                    ->withPivot('urutan')
                    ->orderByPivot('urutan', 'asc');
    }
}