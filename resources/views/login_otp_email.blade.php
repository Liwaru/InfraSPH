<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: 0 24px 50px -32px rgba(69, 28, 0, 0.45);
        }
        .logo { width: min(210px, 68%); display: block; margin: 0 auto 1rem; }
        .title { color: #f25500; font-size: 2rem; font-weight: 800; text-align: center; margin-bottom: 0.4rem; }
        .subtitle { color: #64748b; text-align: center; line-height: 1.6; margin-bottom: 1.4rem; }
        .alert { border-radius: 1rem; padding: 0.85rem 1rem; margin-bottom: 1rem; font-size: 0.85rem; color: #b91c1c; background: #fff1f0; border-left: 5px solid #dc2626; }
        .step-badge { width: max-content; margin: 0 auto 0.8rem; padding: 0.45rem 0.75rem; border-radius: 999px; background: #fff4ec; color: #9a3412; font-size: 0.8rem; font-weight: 800; }
        label { display: block; font-size: 0.86rem; font-weight: 700; color: #1e293b; margin-bottom: 0.55rem; }
        input {
            width: 100%;
            border: 1.5px solid #e9edf2;
            border-radius: 1.2rem;
            padding: 0.9rem 1rem;
            font: inherit;
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
        .back-link { display: block; margin-top: 1rem; color: #f97316; text-align: center; font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>
    <div class="auth-card">
        <img src="{{ asset('images/Infrasph oren.png') }}" alt="Logo InfraSPH" class="logo">
        <div class="step-badge">Tahap 1 dari 2</div>
        <h1 class="title">OTP Email</h1>
        <p class="subtitle">Masukkan email akun. Setelah klik kirim kode, sistem akan mengirim OTP 6 digit ke email tersebut.</p>

        @if ($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.otp.request') }}" method="POST">
            @csrf
            <label for="otp_email">Email</label>
            <input id="otp_email" name="otp_email" type="email" value="{{ old('otp_email') }}" placeholder="nama@email.com" autocomplete="email" required>
            <button type="submit" class="btn">Kirim Kode OTP</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">Kembali ke login</a>
    </div>
</body>
</html>
