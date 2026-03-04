<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - E-Commerce</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .subtitle {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #f8f9fa;
            color: #667eea;
            border: 2px solid #667eea;
        }
        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }
        .features {
            margin-top: 2rem;
            text-align: left;
        }
        .feature {
            margin: 1rem 0;
            padding: 0.5rem 0;
        }
        .feature-icon {
            color: #667eea;
            margin-right: 0.5rem;
        }
        .status {
            margin-top: 1rem;
            padding: 1rem;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🛒 E-Commerce</div>
        <div class="subtitle">Sistem E-Commerce Laravel dengan Filament Admin</div>
        
        <div class="status">
            ✅ Aplikasi berhasil berjalan!<br>
            📊 Database: Terhubung<br>
            🔐 Admin Panel: Siap digunakan
        </div>

        <div style="margin: 2rem 0;">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/admin') }}" class="btn btn-primary">Dashboard Admin</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login Admin</a>
                @endauth
            @endif
            <a href="/admin" class="btn btn-secondary">Panel Admin</a>
        </div>

        <div class="features">
            <div class="feature">
                <span class="feature-icon">🏪</span>
                <strong>Multi-Toko:</strong> Sistem marketplace dengan banyak toko
            </div>
            <div class="feature">
                <span class="feature-icon">📦</span>
                <strong>Manajemen Produk:</strong> CRUD produk dengan upload gambar
            </div>
            <div class="feature">
                <span class="feature-icon">🛒</span>
                <strong>Sistem Pesanan:</strong> Pemesanan dengan tracking pengiriman
            </div>
            <div class="feature">
                <span class="feature-icon">⭐</span>
                <strong>Review & Rating:</strong> Sistem ulasan pelanggan
            </div>
            <div class="feature">
                <span class="feature-icon">🔧</span>
                <strong>Admin Panel:</strong> Interface admin dengan Filament
            </div>
            <div class="feature">
                <span class="feature-icon">📱</span>
                <strong>RESTful API:</strong> API lengkap untuk integrasi mobile
            </div>
        </div>

        <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #eee; color: #666; font-size: 0.9rem;">
            <p><strong>Kredensial Admin:</strong></p>
            <p>Email: admin@example.com<br>Password: password</p>
        </div>
    </div>
</body>
</html>