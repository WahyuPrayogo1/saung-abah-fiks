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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['makanan', 'minuman']); // Atau kamu bisa pakai tabel kategori terpisah
            $table->integer('harga');
            $table->string('foto');
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif'); // Status produk
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
