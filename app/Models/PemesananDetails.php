<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemesanans_id',
        'produk_id',
        'jumlah',
        'harga',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanans::class, 'pemesanans_id');
    }
}
