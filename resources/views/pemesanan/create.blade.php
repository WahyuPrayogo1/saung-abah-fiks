<!-- resources/views/pemesanan/create.blade.php -->

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
        }

        .card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #5c6bc0;
            color: white;
            font-size: 1.5rem;
            border-radius: 8px 8px 0 0;
        }

        .card-body {
            background-color: white;
            padding: 2rem;
            border-radius: 0 0 8px 8px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .btn-primary {
            background-color: #5c6bc0;
            border-color: #5c6bc0;
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #3f51b5;
            border-color: #3f51b5;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .container {
            max-width: 800px;
        }

        .product-card {
            cursor: pointer;
            border: 1px solid #d1d9e6;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 150px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        .product-name {
            font-weight: 600;
            font-size: 1.2rem;
        }

        .product-price {
            font-size: 1rem;
            color: #5c6bc0;
        }

        .selected-product {
            border: 2px solid #5c6bc0;
        }

    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Form Pemesanan</h3>
            </div>
            <div class="card-body">
<!-- resources/views/pemesanan/create.blade.php -->

<form action="{{ route('pemesanans.store') }}" method="POST">
    @csrf

    <!-- Pilih Meja -->
    <div class="mb-3">
        <label for="meja_id" class="form-label">Meja</label>
        <select name="meja_id" id="meja_id" class="form-select">
            @foreach($meja as $item)
                <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>

    <!-- Pilih Produk -->
    <div class="mb-3" id="produk-container">
        <label for="produk_id" class="form-label">Pilih Produk</label>
        <div class="row" id="product-list">
            @foreach($produk as $item)
                <div class="col-md-4 mb-3">
                    <div class="card product-card" data-id="{{ $item->id }}" data-harga="{{ $item->harga }}">
                        <img src="{{ asset('storage/' . $item->foto) }}" class="card-img-top product-image" alt="{{ $item->nama }}">
                        <div class="card-body">
                            <h5 class="product-name">{{ $item->nama }}</h5>
                            <p class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            <!-- Input jumlah yang akan ditampilkan saat produk dipilih -->
                            <input type="number" name="jumlah[]" class="form-control" min="1" placeholder="Jumlah" data-id="{{ $item->id }}" style="display:none;">
                            <!-- Hidden input untuk mengirim ID produk yang dipilih -->
                            <input type="hidden" name="produk_id[]" class="produk_id" value="{{ $item->id }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100">Pesan</button>
</form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Menampilkan SweetAlert jika ada session success
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Pemesanan Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        </script>

    <script>
        // Event listener untuk card produk
        $('.product-card').click(function () {
            var productId = $(this).data('id');
            var productPrice = $(this).data('harga');

            // Menandai produk yang dipilih
            $('.product-card').removeClass('selected-product');
            $(this).addClass('selected-product');

            // Tampilkan input jumlah produk untuk produk yang dipilih
            var jumlahInput = $(this).find('input[name="jumlah[]"]');
            jumlahInput.show().val(1); // Menampilkan input jumlah dengan default 1
        });

        // Menangani form submission untuk memasukkan produk yang dipilih
        $('form').submit(function (e) {
            var selectedProducts = [];
            // Ambil id produk yang dipilih dan jumlah yang dimasukkan
            $('.selected-product').each(function() {
                var productId = $(this).data('id');
                var jumlah = $(this).find('input[name="jumlah[]"]').val();
                selectedProducts.push({ id: productId, jumlah: jumlah });
            });

            // Simpan data produk yang dipilih ke dalam input hidden atau kirim data dalam request
            console.log(selectedProducts);
        });
    </script>

</body>

</html>
