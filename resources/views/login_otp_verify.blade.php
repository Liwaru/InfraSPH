<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ff5900, #ff7a21);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            width: min(440px, 100%);
            background: rgba(255, 255, 255, 0.97);
            border-radius: 2rem;
            padding: 2rem;
            box-shadow: 0 24px 50px -32px rgba(69, 28, 0, 0.45);
        }
        .title { color: #f25500; font-size: 2rem; font-weight: 800; text-align: center; margin-bottom: 0.4rem; }
        .subtitle { color: #64748b; text-align: center; line-height: 1.6; margin-bottom: 1.4rem; }
        .email-pill { background: #fff4ec; color: #9a3412; border-radius: 999px; padding: 0.55rem 0.8rem; text-align: center; font-weight: 700; margin-bottom: 1rem; overflow-wrap: anywhere; }
        .step-badge { width: max-content; margin: 0 auto 0.8rem; padding: 0.45rem 0.75rem; border-radius: 999px; background: #fff4ec; color: #9a3412; font-size: 0.8rem; font-weight: 800; }
        .countdown { text-align: center; color: #64748b; font-size: 0.9rem; font-weight: 700; margin: -0.35rem 0 1rem; }
        .countdown.expired { color: #b91c1c; }
        .alert { border-radius: 1rem; padding: 0.85rem 1rem; margin-bottom: 1rem; font-size: 0.85rem; }
        .alert.success { color: #166534; background: #ecfdf3; border-left: 5px solid #22c55e; }
        .alert.error { color: #b91c1c; background: #fff1f0; border-left: 5px solid #dc2626; }
        label { display: block; font-size: 0.86rem; font-weight: 700; color: #1e293b; margin-bottom: 0.55rem; }
        input {
            width: 100%;
            border: 1.5px solid #e9edf2;
            border-radius: 1.2rem;
            padding: 0.9rem 1rem;
            font: inherit;
            text-align: center;
            letter-spacing: 0.32em;
            font-weight: 800;
            outline: none;
            margin-bottom: 1rem;
        }
        input:focus { border-color: #ff6a00; box-shadow: 0 0 0 3px rgba(255, 106, 0, 0.18); }
        .btn {
            width: 100%;
            border: 0;
            border-radius: 2rem;
            background: linear-gradient(100deg, #f97316, #fd7010);
            color: #fff;
            padding: 0.9rem;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }
        .secondary { margin-top: 0.8rem; background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa; }
    </style>
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
