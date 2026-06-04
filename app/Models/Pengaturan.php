<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';
    
    protected $fillable = [
        'nama_website',
        'logo',
        'no_telp',
        'email',
        'alamat',
        'deskripsi_footer',
        'hero_badge',
        'hero_title',
        'hero_title_highlight',
        'hero_subtitle',
        'hero_images',
        'gallery_images' // <- Tambahan baru di sini
    ];

    protected $casts = [
        'hero_images' => 'array',
        'gallery_images' => 'array', // <- Tambahan baru di sini
    ];
}