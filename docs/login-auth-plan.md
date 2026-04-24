# Rancangan Login Multi Metode InfraSPH

## Prioritas Implementasi

1. `Nama lengkap/username + password` sebagai login utama.
2. `Google login` sebagai opsi tambahan jika sekolah memakai akun Google resmi.
3. `OTP email` sebagai fitur opsional untuk fallback atau verifikasi tambahan.

## Struktur Route

```php
Route::prefix('auth')->group(function () {
    Route::get('/login', [Control::class, 'showLoginForm'])->name('login');
    Route::post('/login/password', [Control::class, 'processLogin'])->name('login.password');
    Route::post('/login/otp/request', [Control::class, 'requestEmailOtp'])->name('login.otp.request');
    Route::post('/login/otp/verify', [Control::class, 'verifyEmailOtp'])->name('login.otp.verify');
    Route::get('/google/redirect', [Control::class, 'redirectToGoogle'])->name('login.google.redirect');
    Route::get('/google/callback', [Control::class, 'handleGoogleCallback'])->name('login.google.callback');
    Route::post('/logout', [Control::class, 'logout'])->name('logout');
});
```

## Flow Controller

### 1. Login utama

- Pengguna mengisi `nama lengkap atau username` dan `password`.
- Sistem mencari akun berdasarkan `users.nama` atau `users.username`.
- Password diverifikasi dengan `Hash::check`.
- Jika valid, session `logged_in` dan data akun diisi.
- Pengguna diarahkan ke `dashboard`, lalu konten dashboard dibedakan menurut role:
  - `1 = ketua kelas`
  - `2 = wali kelas`
  - `3 = superadmin`
  - `4 = kepala sekolah`

### 2. Login OTP email

- Pengguna memasukkan email.
- Sistem cek apakah email ada di tabel `users`.
- Sistem membuat kode OTP 6 digit, menyimpan hash ke tabel `login_otps`, lalu mengirim ke email.
- Pengguna memasukkan email + OTP.
- Sistem cek OTP:
  - belum dipakai
  - belum expired
  - hash cocok
- Jika valid, session login dibuat lalu diarahkan ke dashboard sesuai role.

### 3. Login Google

- Pengguna klik tombol `Masuk dengan Google`.
- Sistem redirect ke Google OAuth.
- Setelah callback, sistem membaca `provider_id` dan email Google.
- Akun hanya boleh login jika email Google sudah cocok dengan akun `users.email`.
- Relasi akun Google disimpan ke tabel `social_accounts`.
- Jika sukses, session login dibuat lalu diarahkan ke dashboard sesuai role.

## Tabel Tambahan

### `users`

Tambahan kolom:

- `username` untuk login alternatif
- `email` untuk OTP dan Google login

### `login_otps`

- `id`
- `id_user`
- `email`
- `otp_code`
- `purpose`
- `expires_at`
- `used_at`
- `requested_ip`
- `user_agent`
- `created_at`
- `updated_at`

### `social_accounts`

- `id`
- `id_user`
- `provider`
- `provider_id`
- `provider_email`
- `provider_name`
- `avatar_url`
- `last_login_at`
- `created_at`
- `updated_at`

## Validasi Error

### Login utama

- identitas wajib diisi
- password wajib diisi
- akun tidak ditemukan
- password salah

### OTP email

- email wajib diisi
- format email tidak valid
- email belum terdaftar
- OTP wajib diisi
- OTP harus 6 digit
- OTP salah
- OTP expired
- OTP sudah dipakai
- mail server belum aktif

### Google login

- konfigurasi OAuth belum diisi
- package Socialite belum dipasang
- callback gagal
- email Google tidak tersedia
- email Google belum cocok dengan akun internal

## Tahapan Pengerjaan yang Disarankan

### Tahap 1

- Selesaikan login `nama lengkap/username + password`
- Pastikan role dan redirect dashboard sudah stabil
- Tambahkan kolom `email`

### Tahap 2

- Aktifkan OTP email
- Uji pengiriman email dari mailer sekolah
- Tambahkan expired time dan pencatatan penggunaan OTP

### Tahap 3

- Pasang `laravel/socialite`
- Buat Google OAuth credential
- Hubungkan email Google dengan akun yang sudah ada

### Tahap 4

- Tambahkan rate limit login
- Tambahkan audit log login
- Tambahkan opsi reset password via email jika diperlukan
