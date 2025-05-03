@extends('backend.master')

@section('content')
<div class="container">
    <h1>Edit Makanan</h1>

    <form action="{{ url('makanan/'.$makanan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Nama Makanan -->
        <div class="form-group mb-3">
            <label for="nama">Nama Makanan</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ $makanan->nama }}" required>
        </div>

        <!-- Harga -->
        <div class="form-group mb-3">
            <label for="harga">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" value="{{ $makanan->harga }}" required>
        </div>

        <!-- Deskripsi -->
        <div class="form-group mb-3">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control">{{ $makanan->deskripsi }}</textarea>
        </div>

        <!-- Foto Makanan -->
        <div class="form-group mb-3">
            <label for="foto">Foto Makanan</label>
            <input type="file" name="foto" id="foto" class="form-control">
            @if($makanan->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/uploads/'.$makanan->foto) }}" alt="Foto Makanan" width="150">
                    <small class="text-muted">Jika ingin mengganti, pilih file baru.</small>
                </div>
            @endif
        </div>

        <!-- Tombol Submit -->
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ url('makanan') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
