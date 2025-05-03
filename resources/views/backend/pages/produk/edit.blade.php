@extends('backend.master')

@section('content')
<div class="container-xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Produk</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('produk/'.$produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Produk -->
                <div class="form-group mb-3">
                    <label for="nama">Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $produk->nama) }}" required>
                </div>

                <!-- Jenis Produk -->
                <div class="form-group mb-3">
                    <label for="jenis">Jenis Produk</label>
                    <select name="jenis" id="jenis" class="form-control" required>
                        <option value="makanan" {{ $produk->jenis == 'makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="minuman" {{ $produk->jenis == 'minuman' ? 'selected' : '' }}>Minuman</option>
                    </select>
                </div>

                <!-- Status Produk -->
                <div class="form-group mb-3">
                    <label for="status">Status Produk</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="aktif" {{ $produk->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ $produk->status == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Harga -->
                <div class="form-group mb-3">
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga', $produk->harga) }}" required>
                </div>

                <!-- Deskripsi -->
                <div class="form-group mb-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>

                <!-- Foto Produk -->
                <div class="form-group mb-3">
                    <label for="foto">Foto Produk</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                    @if($produk->foto)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$produk->foto) }}" alt="Foto Produk" width="150">
                            <small class="text-muted">Jika ingin mengganti, pilih file baru.</small>
                        </div>
                    @endif
                </div>

                <!-- Tombol Submit -->
                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ url('produk') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
