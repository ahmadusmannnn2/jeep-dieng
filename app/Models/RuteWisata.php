<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuteWisata extends Model
{
    use HasFactory;

    protected $table = 'rute_wisata';
    protected $guarded = ['id'];

    public function pakets()
    {
        return $this->belongsToMany(PaketWisata::class, 'paket_rute', 'rute_wisata_id', 'paket_wisata_id')
                    ->withPivot('urutan')
                    ->orderByPivot('urutan', 'asc');
    }
}