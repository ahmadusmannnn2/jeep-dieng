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
}