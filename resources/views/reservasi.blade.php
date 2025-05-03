@extends('layout.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h4>Reservasi Meja</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reservasi.simpan') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="meja_id" class="form-label">Pilih Meja</label>
                            <select name="meja_id" id="meja_id" class="form-select" required>
                                <option value="">-- Pilih Meja --</option>
                                @foreach($mejas as $meja)
                                    @if(!$meja->status)
                                        <option value="{{ $meja->id }}">Meja No. {{ $meja->nomor_meja }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jam</label>
                            <input type="time" name="jam" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Orang</label>
                            <input type="number" name="jumlah_orang" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Reservasi Sekarang</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
