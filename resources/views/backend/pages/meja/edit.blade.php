@extends('backend.master')

@section('content')
<div class="container-xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Meja</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('meja/'.$meja->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Meja -->
                <div class="form-group mb-3">
                    <label for="nama">Nama Meja</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $meja->nama) }}" required>
                </div>

                <!-- Status Ketersediaan -->
                <div class="form-group mb-3">
                    <label for="is_available">Status Ketersediaan</label>
                    <select name="is_available" id="is_available" class="form-control" required>
                        <option value="1" {{ $meja->is_available ? 'selected' : '' }}>Tersedia</option>
                        <option value="0" {{ !$meja->is_available ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>

                <!-- Tombol Submit -->
                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ url('meja') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
