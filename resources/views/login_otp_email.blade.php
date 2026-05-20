<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/views/login_otp_email.css') }}">
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

