<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    protected $table = 'testimonis';
    protected $fillable = ['nama', 'asal_kota', 'pesan', 'is_tampil'];
}
