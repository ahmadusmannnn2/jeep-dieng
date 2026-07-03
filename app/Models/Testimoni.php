<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimoni extends Model
{
    protected $table = 'testimonis';
    protected $fillable = ['nama', 'asal_kota', 'pesan', 'is_tampil', 'rating', 'user_id', 'pesanan_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }
}
