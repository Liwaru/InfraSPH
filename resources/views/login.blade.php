<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Login | Akses Sistem</title>
    <!-- Google Fonts + Font Awesome 6 (Gratis) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #ff5900 0%, #ff5900 45%, #ff5900 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
        }

        /* background animasi lembut */
        body::before {
            content: "";
            position: fixed;
            top: -20%;
            left: -20%;
            width: 140%;
            height: 140%;
            background: radial-gradient(circle, rgba(255, 110, 30, 0.12) 0%, rgba(255, 200, 150, 0) 70%);
            pointer-events: none;
            animation: slowDrift 18s infinite alternate;
        }

        @keyframes slowDrift {
            0% { transform: translate(0, 0) scale(1); opacity: 0.6; }
            100% { transform: translate(3%, 2%) scale(1.02); opacity: 1; }
        }

        /* card utama */
        .login-container {
            width: 100%;
            max-width: 460px;
            z-index: 2;
            animation: fadeSlideUp 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(2px);
            border-radius: 2rem;
            padding: 2rem 1.8rem 2.2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.2), 0 4px 12px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.7);
            transition: box-shadow 0.2s;
        }

        .login-card:hover {
            box-shadow: 0 30px 55px -15px rgba(249, 115, 22, 0.25);
        }

        /* header */
        .brand-header {
            text-align: center;
            margin-bottom: 1.8rem;
        }

        .brand-logo {
            width: min(260px, 75%);
            height: auto;
            display: block;
            margin: 0 auto 0.35rem;
            object-fit: contain;
        }

        .logo-circle {
            background: linear-gradient(135deg, #f97316, #fdba74);
            width: 56px;
            height: 56px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 15px -5px rgba(249, 115, 22, 0.3);
        }

        .logo-circle i {
            font-size: 28px;
            color: white;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        h1 {
            font-size: 1.9rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ea580c, #f97316);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
        }

        .sub-head {
            font-size: 0.85rem;
            color: #5b6e8c;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        /* notifikasi sukses / error */
        .alert-message {
            border-radius: 1.2rem;
            padding: 0.9rem 1rem;
            margin-bottom: 1.6rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            backdrop-filter: blur(4px);
        }

        .alert-success {
            background: #e6f9ed;
            border-left: 5px solid #2b9348;
            color: #146b3a;
        }

        .alert-error {
            background: #fff1f0;
            border-left: 5px solid #dc2626;
            color: #b91c1c;
        }

        .alert-message i {
            font-size: 1.1rem;
        }

        /* form group */
        .input-group {
            margin-bottom: 1.3rem;
            position: relative;
        }

        label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        label i {
            color: #f97316;
            font-size: 0.85rem;
            width: 18px;
        }

        .input-field {
            width: 100%;
            border: 1.5px solid #e9edf2;
            border-radius: 1.2rem;
            background: #ffffff;
            padding: 0.85rem 1rem;
            font-size: 0.95rem;
            font-family: 'Inter', monospace;
            transition: all 0.2s ease;
            outline: none;
            color: #0f172a;
        }

        .input-field:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
        }

        /* password wrapper */
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #f97316;
        }

        .validation-text {
            font-size: 0.7rem;
            color: #dc2626;
            margin-top: 0.4rem;
            margin-left: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* checkbox & lupa password */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0 1.5rem;
            font-size: 0.8rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            accent-color: #f97316;
            cursor: pointer;
        }

        .forgot-link {
            color: #f97316;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .forgot-link:hover {
            color: #ea580c;
            text-decoration: underline;
        }

        /* tombol login */
        .login-btn {
            width: 100%;
            background: linear-gradient(100deg, #f97316, #fd7010);
            border: none;
            border-radius: 2rem;
            padding: 0.9rem;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 6px 14px rgba(249, 115, 22, 0.25);
        }

        .login-btn i {
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .login-btn:hover {
            background: linear-gradient(100deg, #ea580c, #f45c0c);
            transform: scale(1.01);
            box-shadow: 0 12px 20px -8px rgba(249, 115, 22, 0.4);
        }

        .login-btn:active {
            transform: scale(0.98);
        }

        /* footer tambahan */
        .register-prompt {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.8rem;
            color: #475569;
            border-top: 1px solid #edf2f7;
            padding-top: 1.3rem;
        }

        .register-prompt a {
            color: #f97316;
            font-weight: 700;
            text-decoration: none;
        }

        .register-prompt a:hover {
            text-decoration: underline;
        }

        /* responsif */
        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem;
            }
            h1 {
                font-size: 1.7rem;
            }
            .options-row {
                flex-wrap: wrap;
                gap: 0.6rem;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="brand-header">
            <img src="{{ asset('images/Infrasph oren.png') }}" alt="Logo InfraSPH" class="brand-logo">
            <h1>Selamat Datang</h1>
            <h1>Di InfraSPH</h1>
        </div>

        <!-- Notifikasi session success / error -->
        @if (session('success'))
            <div class="alert-message alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->has('login'))
            <div class="alert-message alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ $errors->first('login') }}</span>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <!-- Field Nama -->
            <div class="input-group">
                <label> Nama Lengkap</label>
                <input type="text"
                       id="nama"
                       name="nama"
                       class="input-field"
                       value="{{ old('nama') }}"
                       placeholder="masukkan nama"
                       autocomplete="name">
                @error('nama')
                <div class="validation-text">
                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Field Password + toggle -->
            <div class="input-group">
                <label> Kata Sandi</label>
                <div class="password-wrapper">
                    <input type="password"
                           id="password"
                           name="password"
                           class="input-field"
                           placeholder="Masukkan password"
                           autocomplete="current-password">
                    <button type="button" class="toggle-password" id="togglePasswordBtn">
                        <i class="far fa-eye-slash" id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                <div class="validation-text">
                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Opsional: remember me & lupa password (hanya mempercantik, tidak mempengaruhi backend jika tidak ditambahkan) -->
            <div class="options-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember"> Ingat saya
                </label>
                {{-- <a href="#" class="forgot-link">Lupa password?</a> --}}
            </div>

            <button type="submit" class="login-btn">
                Masuk Sekarang
            </button>
        </form>

    </div>
</div>

<!-- JavaScript sederhana untuk toggle password -->
<script>
    (function() {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (toggleBtn && passwordField) {
            toggleBtn.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                // ganti icon
                if (type === 'text') {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                } else {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                }
            });
        }
    })();
</script>
</body>
</html>
