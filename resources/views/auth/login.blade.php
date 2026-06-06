<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Berkat Kasih</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header {
            margin-bottom: 40px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .store-name {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .store-subtitle {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
            color: #991b1b;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        .login-button {
            width: 100%;
            padding: 12px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* Tambahan Style untuk Akun Demo Pengujian */
        .demo-accounts {
            margin-top: 25px;
            padding: 15px;
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            text-align: left;
        }

        .demo-title {
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
        }

        .demo-item {
            font-size: 13px;
            color: #334155;
            margin-bottom: 4px;
        }

        .demo-item:last-child {
            margin-bottom: 0;
        }

        .demo-label {
            font-weight: 600;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            .store-name {
                font-size: 24px;
            }

            .logo {
                width: 70px;
                height: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <img src="{{ asset('images/icons/Logo.png') }}" alt="Berkat Kasih">
            </div>
            <h1 class="store-name">Berkat Kasih</h1>
            <p class="store-subtitle">Sistem Penjualan Toko</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username"
                    name="username" 
                    placeholder="Masukkan username Anda"
                    value="{{ old('username') }}"
                    required
                    autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    placeholder="Masukkan password Anda"
                    required>
            </div>

            <button type="submit" class="login-button">Login</button>
        </form>

        <div class="demo-accounts">
            <div class="demo-title">🔑 Akun Demo Pengujian</div>
            <div class="demo-item">
                <span class="demo-label">Admin:</span> username <code style="background:#e2e8f0; padding:2px 4px; border-radius:4px;">admin</code> & password <code style="background:#e2e8f0; padding:2px 4px; border-radius:4px;">admin123</code>
            </div>
            <div class="demo-item">
                <span class="demo-label">Pemilik:</span> username <code style="background:#e2e8f0; padding:2px 4px; border-radius:4px;">pemilik</code> & password <code style="background:#e2e8f0; padding:2px 4px; border-radius:4px;">pemilik123</code>
            </div>
        </div>
    </div>
</body>
</html>