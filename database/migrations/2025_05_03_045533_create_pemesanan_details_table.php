<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemesanan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanans_id')->constrained('pemesanans'); // Relasi ke tabel pemesanans
            $table->foreignId('produk_id')->constrained('produks'); // Relasi ke tabel produk
            $table->integer('jumlah')->default(1);  // Jumlah produk yang dipesan
            $table->integer('harga');  // Harga produk per unit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan_details');
    }
};
