<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenInformasi extends Model
{
    use HasFactory;

    protected $table = 'konten_informasi';
    protected $guarded = ['id'];
}