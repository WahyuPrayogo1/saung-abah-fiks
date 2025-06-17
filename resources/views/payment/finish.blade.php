<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --success: #4bb543;
            --warning: #ff9500;
            --danger: #ff3e3e;
            --light: #f8f9fa;
            --dark: #2b2d42;
        }

        body {
            background-color: #f5f7ff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .status-card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
        }

        .status-header {
            background: linear-gradient(135deg, var(--primary) 0%, #3a56d4 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .status-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: white;
        }

        .status-body {
            padding: 30px;
            background-color: white;
            text-align: center;
        }

        .status-alert {
            border-radius: 12px;
            padding: 25px;
            background-color: rgba(75, 181, 67, 0.1);
            border-left: 4px solid var(--success);
        }

        .status-title {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--success);
            margin-bottom: 15px;
        }

        .status-message {
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 20px;
        }

        .status-detail {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .action-button {
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            margin-top: 25px;
            transition: all 0.3s;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            color: white;
        }

        .progress-container {
            width: 100%;
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            margin: 25px 0;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, #3a56d4 100%);
            animation: progress 2s ease-in-out infinite;
            background-size: 200% 100%;
        }

        @keyframes progress {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="status-card">
            <div class="status-header">
                <div class="status-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Status Pembayaran</h2>
            </div>
            <div class="status-body">
                <div class="status-alert">
                    <div class="status-title">Pembayaran Berhasil!</div>
                    <div class="status-message">
                        Terima kasih telah melakukan pembayaran.
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar"></div>
                    </div>
                    <div class="status-detail">
                        <p>Transaksi Anda sedang diproses.</p>
                        <p>Notifikasi akan dikirim ke email Anda setelah pembayaran selesai diverifikasi.</p>
                    </div>
                </div>



                <div class="mt-4 text-muted small">
                    <i class="fas fa-info-circle me-2"></i>
                    Jika Anda memiliki pertanyaan, silakan hubungi tim support kami.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
