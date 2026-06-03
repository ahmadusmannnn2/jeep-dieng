<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'komunitas_id',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi User ke Komunitas (Untuk Admin Komunitas)
    public function komunitas()
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }

    // Relasi User ke Pesanan (Untuk Customer)
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'user_id');
    }
}