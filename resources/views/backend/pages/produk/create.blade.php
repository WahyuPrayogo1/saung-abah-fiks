@extends('backend.master')

@section('content')
<div class="container-xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Produk</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nama Produk -->
                <div class="form-group mb-3">
                    <label for="nama">Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>

                <!-- Jenis Produk -->
                <div class="form-group mb-3">
                    <label for="jenis">Jenis Produk</label>
                    <select name="jenis" id="jenis" class="form-control" required>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                    </select>
                </div>

                <!-- Status Produk -->
                <div class="form-group mb-3">
                    <label for="status">Status Produk</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                    </select>
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

                <!-- Foto Produk -->
                <div class="form-group mb-3">
                    <label for="foto">Foto Produk</label>
                    <input type="file" name="foto" id="foto" class="form-control" required>
                </div>

                <!-- Tombol Submit -->
                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
