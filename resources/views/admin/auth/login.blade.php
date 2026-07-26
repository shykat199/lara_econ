<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} – Sign In</title>

    <link rel="icon" href="{{ asset('build/assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    <link href="{{ asset('build/assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('build/assets/app-dDDo_cMZ.css') }}">
    <link href="{{ asset('build/assets/icon-fonts/icons.css') }}" rel="stylesheet">

    <style>
        :root {
            --brand: #e8442b;
            --brand-dark: #c73520;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f0f0f 0%, #1c1c1c 50%, #2a1a14 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 1rem;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            background: var(--brand);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 1.6rem;
            color: #fff;
        }

        .login-card h1 {
            font-size: 1.45rem;
            font-weight: 700;
            color: #111;
            text-align: center;
            margin-bottom: .25rem;
        }

        .login-card .subtitle {
            text-align: center;
            color: #888;
            font-size: .875rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: #444;
            letter-spacing: .3px;
            margin-bottom: .35rem;
        }

        .form-control {
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            padding: .65rem .9rem;
            font-size: .9rem;
            color: #111;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(232, 68, 43, .12);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: .75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
            line-height: 1;
        }

        .password-toggle:hover { color: var(--brand); }

        .btn-login {
            background: var(--brand);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            font-size: .95rem;
            padding: .7rem;
            width: 100%;
            transition: background .2s, transform .1s;
            letter-spacing: .2px;
        }

        .btn-login:hover {
            background: var(--brand-dark);
            color: #fff;
        }

        .btn-login:active { transform: scale(.98); }

        .btn-login:disabled {
            background: #f0a090;
            cursor: not-allowed;
        }

        .divider-text {
            text-align: center;
            color: #bbb;
            font-size: .78rem;
            position: relative;
            margin: 1.5rem 0;
        }

        .divider-text::before,
        .divider-text::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #e8e8e8;
        }

        .divider-text::before { left: 0; }
        .divider-text::after  { right: 0; }

        .alert-danger {
            border-radius: 10px;
            font-size: .85rem;
            border: none;
            background: #fff0ee;
            color: #c0392b;
            padding: .65rem .9rem;
        }

        .form-check-label {
            font-size: .85rem;
            color: #666;
        }

        @media (max-width: 480px) {
            .login-card { padding: 2rem 1.25rem; }
        }
    </style>
</head>

<body>
<div class="login-wrapper">
    <div class="login-card">

        <div class="brand-logo">
            <i class="ti ti-camera"></i>
        </div>

        <h1>{{ config('app.name') }}</h1>
        <p class="subtitle">Sign in to your admin account</p>

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="ti ti-alert-circle me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success mb-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="admin@example.com"
                    autocomplete="email"
                    autofocus
                    required
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password">
                        <i class="ti ti-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                Sign In
            </button>
        </form>

    </div>
</div>

<script src="{{ asset('build/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'ti ti-eye-off';
        } else {
            input.type = 'password';
            icon.className = 'ti ti-eye';
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'Signing in…';
    });
</script>
</body>
</html>
