<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi User - SMKN 4 BOGOR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Inter',sans-serif;
            background:#f5f5f5;
            color:#333;
            min-height:100vh;
            display:flex;
            flex-direction:column;
            align-items:center;
            padding:20px;
        }
        .register-wrapper {
            width:100%;
            max-width:520px;
            background:#ffffff;
            border-radius:24px;
            box-shadow:0 20px 40px rgba(15,23,42,0.18);
            padding:28px 24px 24px;
        }
        .register-header { text-align:center; margin-bottom:18px; }
        .register-title {
            font-size:1.6rem;
            font-weight:700;
            color:#111827;
            margin-bottom:6px;
        }
        .register-subtitle {
            font-size:0.95rem;
            color:#6b7280;
        }
        .form-group { margin-bottom:18px; }
        .form-input {
            width:100%;
            padding:13px 16px;
            border:2px solid #e5e7eb;
            border-radius:12px;
            font-size:14px;
            transition:all 0.3s ease;
            background:#f9fafb;
        }
        .form-input:focus {
            outline:none;
            border-color:#3b82f6;
            background:#ffffff;
            box-shadow:0 0 0 4px rgba(59,130,246,0.1);
        }
        .label {
            display:block;
            font-size:14px;
            font-weight:600;
            color:#374151;
            margin-bottom:6px;
        }
        .input-error {
            color:#dc2626;
            font-size:12px;
            margin-top:4px;
        }
        .btn-register {
            width:100%;
            background:#3b82f6;
            color:#ffffff;
            border:none;
            padding:13px 24px;
            border-radius:12px;
            font-size:15px;
            font-weight:600;
            cursor:pointer;
            transition:all 0.3s ease;
            text-transform:uppercase;
            letter-spacing:0.5px;
        }
        .btn-register:hover {
            background:#2563eb;
            transform:translateY(-2px);
            box-shadow:0 10px 24px rgba(37,99,235,0.3);
        }
        .footer-link {
            margin-top:16px;
            text-align:center;
            font-size:0.9rem;
            color:#6b7280;
        }
        .footer-link a { color:#2563eb; text-decoration:none; }
        .footer-link a:hover { text-decoration:underline; }
        .captcha-row { display:flex; align-items:center; gap:10px; }
        .captcha-badge {
            background:#eff6ff;
            border-radius:999px;
            padding:8px 16px;
            font-weight:600;
            color:#1d4ed8;
            font-size:0.95rem;
        }
        @media (max-width:480px) {
            .register-wrapper { padding:22px 18px 20px; }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-header">
            <h1 class="register-title">Daftar Akun Baru</h1>
            <p class="register-subtitle">Buat akun untuk mengakses fitur galeri SMKN 4 Bogor.</p>
        </div>

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <div class="form-group">
                <label class="label" for="name">Nama Lengkap</label>
                <input id="name" type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <p class="input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="label" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                @error('email')
                    <p class="input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-input" required>
                @error('password')
                    <p class="input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="label" for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="label">Verifikasi Keamanan</label>
                <div class="captcha-row">
                    <div class="captcha-badge">
                        {{ $a ?? 0 }} + {{ $b ?? 0 }} = ?
                    </div>
                    <input type="number" name="captcha" class="form-input" style="max-width:130px;" placeholder="Jawaban" required>
                </div>
                @error('captcha')
                    <p class="input-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-register">Daftar &amp; Masuk</button>
        </form>

        <div class="footer-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>

        <div class="footer-link" style="margin-top:8px;">
            <a href="{{ route('user.dashboard') }}">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
