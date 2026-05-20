<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ubah Password | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/change_password.css') }}">
</head>
<body>
<div class="app-shell" id="appShell">
    @include('header')

    <main class="content-area">
        <section class="password-card">
            <h1 class="panel-title">Ubah Password</h1>
            <p class="panel-copy">
                Gunakan password baru minimal 6 karakter. Nama dan email tidak ikut berubah saat password diperbarui.
            </p>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                <div class="field-grid">
                    <div>
                        <label for="current_password">Password Lama</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                    </div>
                    <div>
                        <label for="password">Password Baru</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required>
                    </div>
                    <div>
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-shield-check"></i> Ubah Password</button>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>

