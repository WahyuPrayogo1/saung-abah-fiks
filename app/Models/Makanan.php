<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makanan extends Model
{
    use HasFactory;
    // Tentukan nama tabel jika tidak menggunakan konvensi plural
    protected $table = 'makanan';

    protected $fillable = [
        'nama', 'harga', 'deskripsi','foto'
    ];
}
