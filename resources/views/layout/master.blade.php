<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saung Abah - Reservasi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Style -->
    <style>
        body {
            background-color: #f5f5f5;
        }
        .navbar {
            background-color: #2e7d32; /* hijau tua */
        }
        .navbar-brand, .nav-link, .navbar-nav .nav-link.active {
            color: #fff !important;
        }
        .container-box {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 50px;
        }
        footer {
            margin-top: 50px;
            padding: 20px 0;
            text-align: center;
            background-color: #2e7d32;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Saung Abah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Content -->
    <div class="container">
        <div class="container-box">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2025 Saung Abah Sukabumi. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
