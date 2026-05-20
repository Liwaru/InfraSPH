<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Login | Akses Sistem</title>
    <!-- Google Fonts + Font Awesome 6 (Gratis) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/views/login.css') }}">
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="brand-header">
            <img src="{{ asset('images/Infrasph oren.png') }}" alt="Logo InfraSPH" class="brand-logo">
            <h1 class="hero-title">Selamat Datang</h1>
            <p class="hero-subtitle">Di InfraSPH</p>
        </div>

        <!-- Notifikasi session success / error -->
        @if (session('success'))
            <div class="alert-message alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="alert-close" aria-label="Tutup notifikasi">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if ($errors->has('login') || $errors->has('password_login'))
            <div class="alert-message alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ $errors->first('login') ?: $errors->first('password_login') }}</span>
                <button type="button" class="alert-close" aria-label="Tutup notifikasi">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <form action="{{ route('login.password') }}" method="POST" id="loginForm">
            @csrf

            <!-- Field Nama -->
            <div class="input-group">
                <label> Nama</label>
                <input type="text"
                       id="login"
                       name="login"
                       class="input-field"
                       value="{{ old('login') }}"
                       placeholder="masukkan nama"
                       autocomplete="username">
                @error('login')
                <div class="validation-text">
                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Field Password + toggle -->
            <div class="input-group">
                <label> Password</label>
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
            </div>

        <button type="submit" class="login-btn" id="loginSubmitBtn">
            <span id="loginSubmitText">Masuk Sekarang</span>
        </button>
        </form>

        <div class="login-divider">atau masuk dengan</div>

        <div class="login-methods">
            <form action="{{ route('login.otp.email') }}" method="GET" class="method-form">
                <button type="submit" class="method-link email-method" title="Masuk dengan OTP Email" aria-label="Masuk dengan OTP Email">
                    <i class="fas fa-envelope-open-text"></i>
                    <span>Masuk dengan OTP Email</span>
                </button>
            </form>
            <form action="{{ route('login.google.redirect') }}" method="GET" class="method-form">
                <button type="submit" class="method-link google-method" title="Login dengan Akun Google" aria-label="Login dengan Akun Google">
                    <i class="fab fa-google"></i>
                    <span>Login dengan Akun Google</span>
                </button>
            </form>
        </div>

    </div>
</div>

<!-- JavaScript sederhana untuk toggle password -->
<script>
    (function() {
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('loginSubmitBtn');
        const submitText = document.getElementById('loginSubmitText');
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

        if (loginForm && submitBtn && submitText) {
            loginForm.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitText.textContent = 'Memproses...';
            });
        }

        document.querySelectorAll('.alert-close').forEach(function(closeButton) {
            closeButton.addEventListener('click', function() {
                const alert = closeButton.closest('.alert-message');
                if (alert) {
                    alert.remove();
                }
            });
        });
    })();
</script>
</body>
</html>

