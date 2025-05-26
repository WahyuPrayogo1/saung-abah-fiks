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
            transform: scale(1.02);
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
            background-color: #f0f4ff;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            margin: 0 10px;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #5c6bc0;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .selected-products-list {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .selected-product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .remove-product {
            color: #ff5252;
            cursor: pointer;
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
                <form action="{{ route('pemesanans.store') }}" method="POST">
                    @csrf

                    <!-- Pilih Meja -->
                    <div class="mb-3">
                        <label for="meja_id" class="form-label">Meja</label>
                                    <select class="form-select" name="meja_id" {{ $selectedMejaId ? 'disabled' : '' }}>
                        @foreach($meja as $item)
                            <option value="{{ $item->id }}" {{ $selectedMejaId == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    @if($selectedMejaId)
                        <input type="hidden" name="meja_id" value="{{ $selectedMejaId }}">
                    @endif



                    </div>

                    <!-- Daftar Produk yang Dipilih -->
                    <div class="selected-products-list" id="selected-products">
                        <h5>Produk Dipilih</h5>
                        <div id="selected-products-container">
                            <!-- Produk yang dipilih akan muncul di sini -->
                            <p class="text-muted" id="no-products-message">Belum ada produk dipilih</p>
                        </div>
                    </div>

                    <!-- Pilih Produk -->
                    <div class="mb-3" id="produk-container">
                        <label for="produk_id" class="form-label">Pilih Produk</label>
                        <div class="row" id="product-list">
                            @foreach($produk as $item)
                                <div class="col-md-4 mb-3">
                                    <div class="card product-card" data-id="{{ $item->id }}" data-harga="{{ $item->harga }}" data-nama="{{ $item->nama }}">
                                        <img src="{{ asset('storage/' . $item->foto) }}" class="card-img-top product-image" alt="{{ $item->nama }}">
                                        <div class="card-body">
                                            <h5 class="product-name">{{ $item->nama }}</h5>
                                            <p class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
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

        $(document).ready(function() {
            let selectedProducts = [];

            // Event listener untuk card produk
            $('.product-card').click(function() {
                const productId = $(this).data('id');
                const productName = $(this).data('nama');
                const productPrice = $(this).data('harga');

                // Cek apakah produk sudah dipilih
                const existingProduct = selectedProducts.find(p => p.id === productId);

                if (existingProduct) {
                    // Jika sudah ada, tambah jumlah
                    existingProduct.quantity += 1;
                } else {
                    // Jika belum ada, tambahkan ke array
                    selectedProducts.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        quantity: 1
                    });
                }

                updateSelectedProductsUI();
            });

            // Fungsi untuk memperbarui tampilan produk yang dipilih
            function updateSelectedProductsUI() {
                const container = $('#selected-products-container');

                if (selectedProducts.length === 0) {
                    container.html('<p class="text-muted">Belum ada produk dipilih</p>');
                    return;
                }

                let html = '';

                selectedProducts.forEach((product, index) => {
                    html += `
                        <div class="selected-product-item" data-id="${product.id}">
                            <div>
                                <strong>${product.name}</strong>
                                <div>Rp ${product.price.toLocaleString('id-ID')}</div>
                            </div>
                            <div class="quantity-control">
                                <button class="quantity-btn minus-btn" data-index="${index}">-</button>
                                <input type="number" class="quantity-input" min="1" value="${product.quantity}" data-index="${index}">
                                <button class="quantity-btn plus-btn" data-index="${index}">+</button>
                            </div>
                            <div class="total-price">
                                Rp ${(product.price * product.quantity).toLocaleString('id-ID')}
                            </div>
                            <span class="remove-product" data-index="${index}">&times;</span>
                        </div>
                    `;
                });

                container.html(html);

                // Tambahkan hidden inputs untuk form submission
                $('form').find('input[name^="produk_id"]').remove();
                $('form').find('input[name^="jumlah"]').remove();

                selectedProducts.forEach(product => {
                    $('form').append(`<input type="hidden" name="produk_id[]" value="${product.id}">`);
                    $('form').append(`<input type="hidden" name="jumlah[]" value="${product.quantity}">`);
                });
            }

            // Event delegation untuk tombol plus, minus, dan hapus
            $(document).on('click', '.plus-btn', function() {
                const index = $(this).data('index');
                selectedProducts[index].quantity += 1;
                updateSelectedProductsUI();
            });

            $(document).on('click', '.minus-btn', function() {
                const index = $(this).data('index');
                if (selectedProducts[index].quantity > 1) {
                    selectedProducts[index].quantity -= 1;
                    updateSelectedProductsUI();
                }
            });

            $(document).on('change', '.quantity-input', function() {
                const index = $(this).data('index');
                const value = parseInt($(this).val());

                if (value >= 1) {
                    selectedProducts[index].quantity = value;
                    updateSelectedProductsUI();
                } else {
                    $(this).val(selectedProducts[index].quantity);
                }
            });

            $(document).on('click', '.remove-product', function() {
                const index = $(this).data('index');
                selectedProducts.splice(index, 1);
                updateSelectedProductsUI();
            });

            // Validasi form sebelum submit
            $('form').submit(function(e) {
                if (selectedProducts.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih setidaknya satu produk!'
                    });
                }
            });
        });
    </script>
</body>
</html>
