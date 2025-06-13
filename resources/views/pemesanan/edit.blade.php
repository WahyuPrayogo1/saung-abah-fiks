@extends('backend.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Dashboard</div>
                <h2 class="page-title">Edit Pemesanan</h2>
            </div>
        </div>
    </div>
</div>

<div class="container-xl mt-5">
    <div class="row row-deck row-cards">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Pemesanan</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('pemesanans.update', $pemesanan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Meja</label>
                        <select name="meja_id" class="form-control" required>
                            <option value="">Pilih Meja</option>
                            @foreach($mejas as $meja)
                                <option value="{{ $meja->id }}" {{ $pemesanan->meja_id == $meja->id ? 'selected' : '' }}>
                                    {{ $meja->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $pemesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $pemesanan->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $pemesanan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Produk</label>
                        <div id="produk-container">
                            @foreach($pemesanan->details as $index => $detail)
                            <div class="row mb-2 produk-item">
                                <div class="col-md-6">
                                    <select name="produk_id[]" class="form-control" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach($produks as $produk)
                                            <option value="{{ $produk->id }}"
                                                {{ $detail->produk_id == $produk->id ? 'selected' : '' }}>
                                                {{ $produk->nama }} (Rp {{ number_format($produk->harga, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="jumlah[]" class="form-control"
                                        value="{{ $detail->jumlah }}" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    @if($index == 0)
                                        <button type="button" class="btn btn-success btn-sm" id="tambah-produk">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-danger btn-sm hapus-produk">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('pemesanans.table') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Tambah produk
        $('#tambah-produk').click(function() {
            var newItem = `
            <div class="row mb-2 produk-item">
                <div class="col-md-6">
                    <select name="produk_id[]" class="form-control" required>
                        <option value="">Pilih Produk</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}">
                                {{ $produk->nama }} (Rp {{ number_format($produk->harga, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="jumlah[]" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm hapus-produk">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
            $('#produk-container').append(newItem);
        });

        // Hapus produk
        $(document).on('click', '.hapus-produk', function() {
            $(this).closest('.produk-item').remove();
        });
    });
</script>
@endsection
