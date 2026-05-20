<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keamanan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/security.css') }}">
</head>
<body>
<div class="app-shell" id="appShell">
    @include('header')

    <main class="content-area">
        <a class="back-button" href="{{ route('dashboard') }}" onclick="if (window.history.length > 1) { event.preventDefault(); window.history.back(); }">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <section class="security-card">
            <h1 class="panel-title">Keamanan</h1>
            <p class="panel-copy">
                OTP email berada di bagian keamanan karena berpengaruh langsung ke proses login. Saat aktif, login password akan meminta kode OTP sebelum masuk dashboard.
            </p>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif

            <div class="status-row">
                <div>
                    <div class="status-title">Verifikasi Dua Langkah (OTP)</div>
                    <div class="status-copy">Kode OTP dikirim ke email akun saat login.</div>
                </div>
                <span class="badge {{ ($user['otp_enabled'] ?? false) ? 'on' : 'off' }}">
                    {{ ($user['otp_enabled'] ?? false) ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>

            <form action="{{ route('profile.otp.update') }}" method="POST">
                @csrf
                <input type="hidden" name="otp_context" value="security_page">
                <label class="switch-line">
                    <input type="checkbox" name="otp_enabled" value="1" {{ ($user['otp_enabled'] ?? false) ? 'checked' : '' }}>
                    Aktifkan OTP email saat login
                </label>
                <div class="actions">
                    <button type="submit" class="btn">Simpan Keamanan</button>
                    <a href="{{ route('login.google.redirect') }}" class="btn google">
                        <i class="bi bi-google"></i>
                        Hubungkan Login Google
                    </a>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>

