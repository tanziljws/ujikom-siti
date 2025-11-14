<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Login - SMKN 4 BOGOR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            color: #111827;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(15,23,42,0.18);
            padding: 28px 26px 26px;
        }
        .title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }
        .subtitle {
            font-size: 0.95rem;
            color: #6b7280;
            margin-bottom: 20px;
        }
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 18px;
        }
        .login-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        .login-option-main {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .login-icon {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e0edff;
            color: #2563eb;
        }
        .login-text-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #111827;
        }
        .login-text-sub {
            font-size: 0.8rem;
            color: #6b7280;
        }
        .btn-small {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 999px;
            border: none;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-admin {
            background: #2563eb;
            color: #ffffff;
        }
        .btn-user {
            background: #10b981;
            color: #ffffff;
        }
        .btn-register {
            margin-top: 4px;
            width: 100%;
            justify-content: center;
            background: #f97316;
            color: #ffffff;
        }
        .back-link {
            margin-top: 12px;
            text-align: center;
            font-size: 0.85rem;
        }
        .back-link a {
            color: #6b7280;
            text-decoration: none;
        }
        .back-link a:hover {
            color: #2563eb;
        }
        @media (max-width: 480px) {
            .card { padding: 22px 18px 20px; }
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="title">Pilih Jenis Login</h1>
        <p class="subtitle">Silakan pilih sebagai apa kamu ingin masuk ke sistem galeri SMKN 4 Bogor.</p>

        <div class="btn-group">
            <div class="login-option">
                <div class="login-option-main">
                    <div class="login-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <div class="login-text-title">Login Admin / Petugas</div>
                        <div class="login-text-sub">Untuk pengelola galeri dan konten website.</div>
                    </div>
                </div>
                <a href="{{ route('login') }}" class="btn-small btn-admin">
                    <i class="fas fa-arrow-right"></i>
                    <span>Masuk</span>
                </a>
            </div>

            <div class="login-option">
                <div class="login-option-main">
                    <div class="login-icon" style="background:#dcfce7;color:#16a34a;">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <div class="login-text-title">Login User</div>
                        <div class="login-text-sub">Untuk akun siswa/guru yang sudah terdaftar.</div>
                    </div>
                </div>
                <a href="{{ route('login') }}" class="btn-small btn-user">
                    <i class="fas fa-arrow-right"></i>
                    <span>Masuk</span>
                </a>
            </div>
        </div>

        <a href="{{ route('register') }}" class="btn-small btn-register">
            <i class="fas fa-user-plus"></i>
            <span>Daftar Akun Baru</span>
        </a>

        <div class="back-link">
            <a href="{{ route('user.dashboard') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
