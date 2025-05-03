<!-- resources/views/surat_keterangan_pindah_domisili/create.blade.php -->
@extends('backend.master')

@section('content')
<div class="container">
    <h1>Tambah Makanan</h1>
    <form action="{{ route('makanan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nama -->
        <div class="form-group mb-3">
            <label for="nama">Nama Makanan</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>

        <!-- Harga -->
        <div class="form-group mb-3">
            <label for="harga">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" required>
        </div>

        <!-- Deskripsi -->
        <div class="form-group mb-3">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
        </div>

        <!-- Foto -->
        <div class="form-group mb-3">
            <label for="foto">Foto Makanan</label>
            <input type="file" name="foto" id="foto" class="form-control" required>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('makanan.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
