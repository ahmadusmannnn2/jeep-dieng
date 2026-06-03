<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jeep extends Model
{
    use HasFactory;

    protected $table = 'jeep';
    protected $guarded = ['id'];

    public function komunitas()
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'jeep_id');
    }
}