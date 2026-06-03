<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuteWisata extends Model
{
    use HasFactory;

    protected $table = 'rute_wisata';
    protected $guarded = ['id'];

    public function komunitas()
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }
}