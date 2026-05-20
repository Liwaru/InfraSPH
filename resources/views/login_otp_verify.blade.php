<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/login_otp_verify.css') }}">
</head>
<body>
    <div class="auth-card">
        <div class="step-badge">Tahap 2 dari 2</div>
        <h1 class="title">Masukkan OTP</h1>
        <p class="subtitle">Kode 6 digit sudah dikirim ke email akun Anda dan berlaku selama 5 menit.</p>
        <div class="email-pill">{{ $email }}</div>
        <div class="countdown" id="otpCountdown" data-expires-at="{{ $expiresAt }}">Kode aktif selama 05:00</div>

        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.otp.verify') }}" method="POST">
            @csrf
            <input type="hidden" name="otp_email" value="{{ $email }}">
            <label for="otp_code">Kode OTP</label>
            <input id="otp_code" name="otp_code" type="text" inputmode="numeric" maxlength="6" placeholder="000000" autocomplete="one-time-code" required>
            <button type="submit" class="btn">Verifikasi</button>
        </form>

        <form action="{{ route('login.otp.request') }}" method="POST">
            @csrf
            <input type="hidden" name="otp_email" value="{{ $email }}">
            <button type="submit" class="btn secondary">Kirim Ulang Kode</button>
        </form>
    </div>
    <script>
        (function () {
            const countdown = document.getElementById('otpCountdown');
            if (!countdown) {
                return;
            }

            const expiresAt = new Date(countdown.getAttribute('data-expires-at') || '').getTime();
            if (!expiresAt) {
                countdown.textContent = 'Kode berlaku selama 5 menit.';
                return;
            }

            function renderCountdown() {
                const remaining = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
                const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
                const seconds = String(remaining % 60).padStart(2, '0');

                if (remaining <= 0) {
                    countdown.textContent = 'Kode OTP sudah kedaluwarsa. Kirim ulang kode.';
                    countdown.classList.add('expired');
                    return;
                }

                countdown.textContent = 'Kode aktif selama ' + minutes + ':' + seconds;
                window.setTimeout(renderCountdown, 1000);
            }

            renderCountdown();
        })();
    </script>
</body>
</html>

