<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKeberangkatan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_keberangkatan';
    protected $guarded = ['id'];

    public function komunitas()
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'jadwal_id');
    }
}