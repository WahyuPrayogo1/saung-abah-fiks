@extends('backend.master')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Order Management</div>
                <h2 class="page-title">Edit Order</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('pemesanans.table') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-xl mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Order Details</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('pemesanans.update', $pemesanan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold">Table</label>
                            <select name="meja_id" class="form-select form-select-lg" required>
                                <option value="">Select Table</option>
                                @foreach($mejas as $meja)
                                    <option value="{{ $meja->id }}" {{ $pemesanan->meja_id == $meja->id ? 'selected' : '' }}>
                                        {{ $meja->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Order Status</label>
                            <select name="status" class="form-select form-select-lg" required>
                                <option value="pending" {{ $pemesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $pemesanan->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $pemesanan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold">Order Items</label>
                                <button type="button" class="btn btn-sm btn-success" id="tambah-produk">
                                    <i class="fas fa-plus me-1"></i> Add Item
                                </button>
                            </div>

                            <div id="produk-container" class="border rounded p-3">
                                @foreach($pemesanan->details as $index => $detail)
                                <div class="row mb-3 produk-item align-items-center">
                                    <div class="col-md-6">
                                        <select name="produk_id[]" class="form-select" required>
                                            <option value="">Select Product</option>
                                            @foreach($produks as $produk)
                                                <option value="{{ $produk->id }}"
                                                    {{ $detail->produk_id == $produk->id ? 'selected' : '' }}
                                                    data-price="{{ $produk->harga }}">
                                                    {{ $produk->nama }} (Rp {{ number_format($produk->harga, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="jumlah[]" class="form-control quantity-input"
                                            value="{{ $detail->jumlah }}" min="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="item-price">Rp {{ number_format($detail->produk->harga * $detail->jumlah, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-md-1 text-end">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-danger hapus-produk">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                                <a href="{{ route('pemesanans.table') }}" class="btn btn-outline-secondary btn-lg ms-2">
                                    Cancel
                                </a>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-1">Subtotal: <span id="subtotal">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span></h5>
                                <small class="text-muted">Tax and fees may apply</small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Summary</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order ID:</span>
                        <strong>#{{ $pemesanan->id }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Date:</span>
                        <strong>{{ $pemesanan->created_at->format('d M Y H:i') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-{{ $pemesanan->status == 'completed' ? 'success' : ($pemesanan->status == 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($pemesanan->status) }}
                        </span>
                    </div>
                    <hr>
                    <h5 class="mb-3">Current Items:</h5>
                    <div class="list-group list-group-flush">
                        @foreach($pemesanan->details as $detail)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ $detail->produk->nama }} × {{ $detail->jumlah }}</span>
                                <span>Rp {{ number_format($detail->produk->harga * $detail->jumlah, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Add product row
        $('#tambah-produk').click(function() {
            var newItem = `
            <div class="row mb-3 produk-item align-items-center">
                <div class="col-md-6">
                    <select name="produk_id[]" class="form-select" required>
                        <option value="">Select Product</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}" data-price="{{ $produk->harga }}">
                                {{ $produk->nama }} (Rp {{ number_format($produk->harga, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="jumlah[]" class="form-control quantity-input" value="1" min="1" required>
                </div>
                <div class="col-md-2">
                    <span class="item-price">Rp 0</span>
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger hapus-produk">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
            $('#produk-container').append(newItem);
        });

        // Remove product row
        $(document).on('click', '.hapus-produk', function() {
            $(this).closest('.produk-item').remove();
            calculateSubtotal();
        });

        // Calculate price when product or quantity changes
        $(document).on('change', 'select[name="produk_id[]"], .quantity-input', function() {
            const row = $(this).closest('.produk-item');
            const price = row.find('select[name="produk_id[]"] option:selected').data('price') || 0;
            const quantity = row.find('.quantity-input').val() || 0;
            const total = price * quantity;

            row.find('.item-price').text('Rp ' + total.toLocaleString('id-ID'));
            calculateSubtotal();
        });

        // Calculate subtotal
        function calculateSubtotal() {
            let subtotal = 0;
            $('.produk-item').each(function() {
                const price = $(this).find('select[name="produk_id[]"] option:selected').data('price') || 0;
                const quantity = $(this).find('.quantity-input').val() || 0;
                subtotal += price * quantity;
            });

            $('#subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
        }

        // Initialize calculations
        calculateSubtotal();
    });
</script>

<style>
    .form-select-lg, .form-control {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
    }

    .produk-item {
        transition: all 0.3s ease;
    }

    .produk-item:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }

    .item-price {
        font-weight: 600;
        color: #4361ee;
    }

    #produk-container {
        background-color: #0054A6;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
    }
</style>
@endsection
