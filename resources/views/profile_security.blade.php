<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil & Keamanan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/profile_security.css') }}">
</head>
<body>
<div class="app-shell" id="appShell">
    @include('header')

    <main class="content-area">
        <div class="page-shell">
            <section class="hero-card">
                <div class="eyebrow"><i class="bi bi-person-gear"></i> Profil & Keamanan</div>
                <h1 class="hero-title">Kelola akun {{ $user['nama'] ?? 'pengguna' }}.</h1>
                <p class="hero-subtitle">Profil berisi identitas akun, sedangkan keamanan berisi perubahan password dan OTP email untuk login dua langkah.</p>
            </section>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <section class="profile-grid">
                <div class="panel-card" id="profil">
                    <h2 class="panel-title">Profil</h2>

                    @if ($canEditIdentity)
                        <p class="panel-copy">Wali kelas dapat memperbarui nama dan email. Penugasan ruangan ditampilkan terpisah agar tidak tertukar dengan identitas akun.</p>
                        <form action="{{ route('profile.identity.update') }}" method="POST">
                            @csrf
                            <div class="field-grid two">
                                <div>
                                    <label for="nama">Nama</label>
                                    <input id="nama" name="nama" type="text" value="{{ old('nama', $user['nama'] ?? '') }}" required>
                                </div>
                                <div>
                                    <label for="email">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email', $user['email'] ?? '') }}" required>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn"><i class="bi bi-check2-circle"></i> Simpan Profil</button>
                            </div>
                        </form>
                    @else
                        <p class="panel-copy">Untuk kepala sekolah dan superadmin, nama dan email ditampilkan sebagai informasi akun. Perubahan yang tersedia dari halaman ini hanya password dan OTP.</p>
                        <div class="readonly-list">
                            <div class="readonly-item">
                                <div class="readonly-label">Nama</div>
                                <div class="readonly-value">{{ $user['nama'] ?? '-' }}</div>
                            </div>
                            <div class="readonly-item">
                                <div class="readonly-label">Email</div>
                                <div class="readonly-value">{{ $user['email'] ?? 'Belum diisi' }}</div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="panel-card" id="ubah-password">
                    <h2 class="panel-title">Ubah Password</h2>
                    <p class="panel-copy">Gunakan password baru minimal 6 karakter. Nama dan email tidak ikut berubah saat password diperbarui.</p>
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
                </div>

                @if ($canEditIdentity)
                    <div class="panel-card">
                        <h2 class="panel-title">Penugasan Ruangan</h2>
                        <p class="panel-copy">Bagian ini menggantikan istilah “kelas yang diampu” agar cocok untuk kelas, laboratorium, atau ruangan lain.</p>
                        <div class="assignment-list">
                            @forelse ($assignments as $assignment)
                                <div class="assignment-item">
                                    <div class="assignment-name">{{ $assignment['nama_ruangan'] }} ({{ $assignment['kode_ruangan'] }})</div>
                                    <div class="assignment-meta">{{ $assignment['jenis_ruangan'] }} · {{ $assignment['peran_ruangan'] }}</div>
                                </div>
                            @empty
                                <div class="assignment-item">
                                    <div class="assignment-name">Belum ada penugasan ruangan aktif.</div>
                                    <div class="assignment-meta">Hubungi superadmin untuk menambahkan penugasan.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <div class="panel-card">
                    <h2 class="panel-title">Keamanan</h2>
                    <p class="panel-copy">OTP email berada di bagian keamanan karena berpengaruh langsung ke proses login. Saat aktif, login password akan meminta kode OTP sebelum masuk dashboard.</p>

                    <div class="status-row">
                        <div>
                            <strong>Verifikasi Dua Langkah (OTP)</strong>
                            <div class="panel-copy otp-copy">Kode OTP dikirim ke email akun saat login.</div>
                        </div>
                        <span class="badge {{ ($user['otp_enabled'] ?? false) ? 'on' : 'off' }}">
                            {{ ($user['otp_enabled'] ?? false) ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>

                    <form action="{{ route('profile.otp.update') }}" method="POST">
                        @csrf
                        <label class="switch-line">
                            <input type="checkbox" name="otp_enabled" value="1" {{ ($user['otp_enabled'] ?? false) ? 'checked' : '' }}>
                            Aktifkan OTP email saat login
                        </label>
                        <div class="form-actions">
                            <button type="submit" class="btn secondary"><i class="bi bi-envelope-lock"></i> Simpan Keamanan</button>
                        </div>
                    </form>

                    <div class="form-actions">
                        <a href="{{ route('login.google.link') }}" class="btn secondary"><i class="bi bi-google"></i> Hubungkan Login Google</a>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>

