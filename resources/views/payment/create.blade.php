<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #e6e9ff;
            --dark: #2b2d42;
            --light: #f8f9fa;
            --success: #4bb543;
            --warning: #ff9500;
            --danger: #ff3e3e;
        }

        body {
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f2ff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .payment-card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.15);
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
        }

        .payment-header {
            background: linear-gradient(135deg, var(--primary) 0%, #3a56d4 100%);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }

        .payment-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 20px;
            background-color: white;
            border-radius: 16px 16px 0 0;
        }

        .payment-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0;
        }

        .payment-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: white;
        }

        .payment-body {
            padding: 30px;
            background-color: white;
        }

        .order-summary {
            background-color: var(--primary-light);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .order-number {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .order-detail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .order-total {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-top: 15px;
        }

        .pay-button {
            background: linear-gradient(135deg, var(--primary) 0%, #3a56d4 100%);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            color: white;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
            color: white;
        }

        .pay-button:active {
            transform: translateY(0);
        }

        .payment-methods {
            margin-top: 25px;
        }

        .method-title {
            font-size: 0.9rem;
            color: #6c757d;
            text-align: center;
            margin-bottom: 15px;
            position: relative;
        }

        .method-title::before,
        .method-title::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e9ecef;
            margin: auto;
        }

        .method-title::before {
            margin-right: 10px;
        }

        .method-title::after {
            margin-left: 10px;
        }

        .method-badge {
            display: inline-flex;
            align-items: center;
            background-color: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 8px 15px;
            margin: 5px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .method-badge:hover {
            border-color: var(--primary);
        }

        .method-badge i {
            margin-right: 8px;
            color: var(--primary);
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-card">
            <div class="payment-header">
                <div class="payment-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h1 class="payment-title">Pembayaran Online</h1>
            </div>
            <div class="payment-body">
                <div class="order-summary">
                    <div class="order-number">Pesanan #{{ $pemesanan->id }}</div>
                    <div class="order-detail">
                        <span>Meja:</span>
                        <span><strong>{{ $pemesanan->meja->nama }}</strong></span>
                    </div>
                    <div class="order-detail">
                        <span>Tanggal:</span>
                        <span>{{ date('d/m/Y H:i') }}</span>
                    </div>
                    <div class="order-total text-end">
                        Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                    </div>
                </div>

                <button id="pay-button" class="pay-button">
                    <span id="button-text">Bayar Sekarang</span>
                    <div id="loading-spinner" class="loading-spinner"></div>
                </button>

                <div class="payment-methods">
                    <div class="method-title">Metode Pembayaran Tersedia</div>
                    <div class="d-flex flex-wrap justify-content-center">
                        <span class="method-badge">
                            <i class="fab fa-cc-visa"></i> Visa
                        </span>
                        <span class="method-badge">
                            <i class="fab fa-cc-mastercard"></i> Mastercard
                        </span>
                        <span class="method-badge">
                            <i class="fas fa-mobile-alt"></i> Gopay
                        </span>
                        <span class="method-badge">
                            <i class="fas fa-university"></i> Transfer Bank
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            const button = this;
            const buttonText = document.getElementById('button-text');
            const spinner = document.getElementById('loading-spinner');

            // Show loading state
            buttonText.textContent = 'Memproses...';
            spinner.style.display = 'block';
            button.disabled = true;

            // Process payment
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = "{{ route('payment.finish') }}?status=success";
                },
                onPending: function(result) {
                    window.location.href = "{{ route('payment.finish') }}?status=pending";
                },
                onError: function(result) {
                    resetButton();
                    window.location.href = "{{ route('payment.finish') }}?status=error";
                },
                onClose: function() {
                    resetButton();
                }
            });

            function resetButton() {
                buttonText.textContent = 'Bayar Sekarang';
                spinner.style.display = 'none';
                button.disabled = false;
            }
        });
    </script>
</body>
</html>
