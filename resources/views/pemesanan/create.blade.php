<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - New Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #f8f9fa;
            --light: #ffffff;
            --dark: #212529;
            --border: #e9ecef;
            --success: #4bb543;
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .pos-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .pos-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .pos-card-header {
            background-color: var(--primary);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .pos-card-body {
            padding: 25px;
            background-color: var(--light);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid var(--border);
        }

        .btn-pos {
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-pos:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-pos:active {
            transform: translateY(0);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .product-card {
            border-radius: 8px;
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
            background-color: white;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            border-color: var(--primary);
        }

        .product-card.selected {
            border: 2px solid var(--primary);
            background-color: rgba(67, 97, 238, 0.05);
        }

        .product-image {
            height: 120px;
            object-fit: cover;
            width: 100%;
        }

        .product-info {
            padding: 12px;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-price {
            color: var(--primary);
            font-weight: 700;
        }

        .selected-items {
            background-color: white;
            border-radius: 8px;
            border: 1px solid var(--border);
            padding: 15px;
            margin-top: 20px;
        }

        .selected-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
        }

        .selected-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: 500;
        }

        .item-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .item-qty {
            width: 50px;
            text-align: center;
            border: 1px solid var(--border);
            border-radius: 5px;
            padding: 5px;
        }

        .qty-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .remove-item {
            color: #dc3545;
            cursor: pointer;
            margin-left: 10px;
        }

        .payment-methods {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .payment-method {
            flex: 1;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.2s;
            background-color: white;
        }

        .payment-method:hover {
            border-color: var(--primary);
        }

        .payment-method.selected {
            border-color: var(--primary);
            background-color: rgba(67, 97, 238, 0.05);
        }

        .payment-method i {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .payment-method-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .payment-method-desc {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #adb5bd;
        }

        .summary-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid var(--border);
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-total {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary);
            border-top: 1px solid var(--border);
            padding-top: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="pos-container">
        <div class="pos-card">
            <div class="pos-card-header d-flex justify-content-between align-items-center">
                <span>New Order</span>
                <span class="badge bg-light text-dark">Table: {{ $selectedMejaId ? $meja->find($selectedMejaId)->nama : 'Select table' }}</span>
            </div>
            <div class="pos-card-body">
                <form action="{{ route('pemesanans.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Table Selection -->
                            <div class="mb-4">
                                <label class="form-label">Select Table</label>
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

                            <!-- Products Grid -->
                            <div class="mb-4">
                                <label class="form-label">Select Products</label>
                                <div class="product-grid">
                                    @foreach($produk as $item)
                                        <div class="product-card" data-id="{{ $item->id }}" data-harga="{{ $item->harga }}" data-nama="{{ $item->nama }}">
                                            <img src="{{ asset('storage/' . $item->foto) }}" class="product-image" alt="{{ $item->nama }}">
                                            <div class="product-info">
                                                <div class="product-name">{{ $item->nama }}</div>
                                                <div class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Order Summary -->
                            <div class="selected-items">
                                <h5 class="mb-3">Order Summary</h5>
                                <div id="selected-products-container">
                                    <div class="empty-state">
                                        <i class="fas fa-shopping-basket"></i>
                                        <p>No items selected</p>
                                    </div>
                                </div>

                                <div class="summary-card d-none" id="order-summary">
                                    <div class="summary-row">
                                        <span>Subtotal:</span>
                                        <span id="subtotal">Rp 0</span>
                                    </div>

                                    <div class="summary-row summary-total">
                                        <span>Total:</span>
                                        <span id="total">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mt-4">
                                <label class="form-label">Payment Method</label>
                                <div class="payment-methods">
                                    <div class="payment-method selected" data-method="cash">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <div class="payment-method-title">Cash</div>
                                        <div class="payment-method-desc">Pay at counter</div>
                                        <input type="radio" name="pembayaran" value="kasir" checked hidden>
                                    </div>
                                    <div class="payment-method" data-method="online">
                                        <i class="fas fa-credit-card"></i>
                                        <div class="payment-method-title">Online</div>
                                        <div class="payment-method-desc">Pay now</div>
                                        <input type="radio" name="pembayaran" value="online" hidden>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-pos w-100 mt-4">
                                <i class="fas fa-paper-plane me-2"></i> Place Order
                            </button>
                        </div>
                    </div>

                    <!-- Hidden inputs for products -->
                    <div id="product-inputs"></div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let selectedProducts = [];
        

            // Product selection
            $('.product-card').click(function() {
                const productId = $(this).data('id');
                const productName = $(this).data('nama');
                const productPrice = $(this).data('harga');

                // Check if product already selected
                const existingProduct = selectedProducts.find(p => p.id === productId);

                if (existingProduct) {
                    existingProduct.quantity += 1;
                } else {
                    selectedProducts.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        quantity: 1
                    });
                }

                updateSelectedProductsUI();
            });

            // Update selected products UI
            function updateSelectedProductsUI() {
                const container = $('#selected-products-container');
                const summary = $('#order-summary');

                if (selectedProducts.length === 0) {
                    container.html(`
                        <div class="empty-state">
                            <i class="fas fa-shopping-basket"></i>
                            <p>No items selected</p>
                        </div>
                    `);
                    summary.addClass('d-none');
                    return;
                }

                let html = '';
                let subtotal = 0;

                selectedProducts.forEach((product, index) => {
                    const itemTotal = product.price * product.quantity;
                    subtotal += itemTotal;

                    html += `
                        <div class="selected-item" data-id="${product.id}">
                            <div class="item-name">${product.name}</div>
                            <div class="item-controls">
                                <button class="qty-btn minus" data-index="${index}">-</button>
                                <input type="number" class="item-qty" value="${product.quantity}" min="1" data-index="${index}">
                                <button class="qty-btn plus" data-index="${index}">+</button>
                                <span class="remove-item" data-index="${index}"><i class="fas fa-times"></i></span>
                            </div>
                            <div class="item-price">Rp ${(itemTotal).toLocaleString('id-ID')}</div>
                        </div>
                    `;
                });

                container.html(html);
                summary.removeClass('d-none');

                // Calculate totals

                const total = subtotal ;

                $('#subtotal').text(`Rp ${subtotal.toLocaleString('id-ID')}`);

                $('#total').text(`Rp ${total.toLocaleString('id-ID')}`);

                // Update hidden inputs
                $('#product-inputs').empty();
                selectedProducts.forEach(product => {
                    $('#product-inputs').append(`<input type="hidden" name="produk_id[]" value="${product.id}">`);
                    $('#product-inputs').append(`<input type="hidden" name="jumlah[]" value="${product.quantity}">`);
                });
            }

            // Quantity controls
            $(document).on('click', '.qty-btn.plus', function() {
                const index = $(this).data('index');
                selectedProducts[index].quantity += 1;
                updateSelectedProductsUI();
            });

            $(document).on('click', '.qty-btn.minus', function() {
                const index = $(this).data('index');
                if (selectedProducts[index].quantity > 1) {
                    selectedProducts[index].quantity -= 1;
                    updateSelectedProductsUI();
                }
            });

            $(document).on('change', '.item-qty', function() {
                const index = $(this).data('index');
                const value = parseInt($(this).val());

                if (value >= 1) {
                    selectedProducts[index].quantity = value;
                    updateSelectedProductsUI();
                } else {
                    $(this).val(selectedProducts[index].quantity);
                }
            });

            $(document).on('click', '.remove-item', function() {
                const index = $(this).data('index');
                selectedProducts.splice(index, 1);
                updateSelectedProductsUI();
            });

            // Payment method selection
            $('.payment-method').click(function() {
                $('.payment-method').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('input[type="radio"]').prop('checked', true);
            });

            // Form validation
            $('form').submit(function(e) {
                if (selectedProducts.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Empty Order',
                        text: 'Please select at least one product',
                        confirmButtonColor: 'var(--primary)'
                    });
                }
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonColor: 'var(--primary)'
                });
            @endif
        });
    </script>
</body>
</html>
