<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/profile.css') }}">
</head>
<body>
<div class="app-shell" id="appShell">
    @include('header')

    <main class="content-area">
        <a class="back-button" href="{{ route('dashboard') }}" onclick="if (window.history.length > 1) { event.preventDefault(); window.history.back(); }">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <section class="profile-card">
            <h1 class="panel-title">Profil</h1>
            <p class="panel-copy">
                Untuk kepala sekolah dan superadmin, nama dan email ditampilkan sebagai informasi akun. Perubahan yang tersedia dari halaman ini hanya password dan OTP.
            </p>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            <div class="readonly-list">
                <div class="readonly-item">
                    <div class="readonly-label">Nama</div>
                    <div class="readonly-value">{{ $user['nama'] ?? '-' }}</div>
                </div>
                <div class="readonly-item">
                    <div class="readonly-label">Email</div>
                    <div class="readonly-value">{{ $user['email'] ?? 'Belum diisi' }}</div>
                </div>
                <div class="readonly-item">
                    <div class="readonly-row">
                        <div>
                            <div class="readonly-label">Password</div>
                            <div class="readonly-value">Password tersimpan aman</div>
                        </div>
                        <button type="button" class="password-action" id="openPasswordModal">
                            <i class="bi bi-shield-lock"></i>
                            Ubah
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="modal-backdrop" id="passwordModal" aria-hidden="true">
        <section class="password-modal" role="dialog" aria-modal="true" aria-labelledby="passwordModalTitle">
            <button type="button" class="modal-close" id="closePasswordModal" aria-label="Tutup popup">
                <i class="bi bi-x-lg"></i>
            </button>

            <h2 class="panel-title" id="passwordModalTitle">Ubah Password</h2>
            <p class="panel-copy">
                Gunakan password baru minimal 6 karakter. Nama dan email tidak ikut berubah saat password diperbarui.
            </p>

            @if ($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="password_context" value="profile_modal">
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
    </div>
</div>
<script>
    (function () {
        const modal = document.getElementById('passwordModal');
        const openButton = document.getElementById('openPasswordModal');
        const closeButton = document.getElementById('closePasswordModal');
        const firstInput = document.getElementById('current_password');
        const shouldOpen = new URLSearchParams(window.location.search).get('open') === 'password' || @json($errors->any());

        function openModal() {
            if (!modal) {
                return;
            }

            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            setTimeout(function () {
                firstInput?.focus();
            }, 50);
        }

        function closeModal() {
            if (!modal) {
                return;
            }

            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        }

        openButton?.addEventListener('click', openModal);
        closeButton?.addEventListener('click', closeModal);

        modal?.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        if (shouldOpen) {
            openModal();
        }
    })();
</script>
</body>
</html>

