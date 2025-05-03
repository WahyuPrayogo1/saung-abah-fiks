<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanans extends Model
{
    use HasFactory;
    protected $fillable = [
        'meja_id',
        'waktu_pemesanan',
        'total_harga',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(PemesananDetails::class, 'pemesanans_id');
    }

    // App\Models\Pemesanan.php
    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

}
