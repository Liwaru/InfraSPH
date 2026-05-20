<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengajuan Kelas | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/pengajuan_masuk_wali.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="inbox-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengguna' }}</div>
                    <h1 class="hero-title">Pengajuan Kelas</h1>
                    <p class="hero-subtitle">
                        Kelola dan verifikasi permintaan dari kelas yang Anda pegang. Pilih pengajuan yang menunggu, lalu setujui atau tolak dengan alasan yang jelas.
                    </p>
                </section>

                <div class="feedback-stack">
                    @if (session('success'))
                        <div class="success-banner">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="error-banner">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="error-banner">
                            Ada data yang perlu diperiksa lagi, terutama saat mengirim alasan penolakan.
                        </div>
                    @endif
                </div>

                @if ($requests->isEmpty())
                    <section class="empty-card">
                        <div class="empty-title">Tidak ada pengajuan</div>
                        <div class="empty-copy">Tidak ada pengajuan yang perlu diverifikasi saat ini.</div>
                    </section>
                @else
                    <section class="request-stack">
                        @foreach ($requests as $request)
                            <article class="request-card">
                                <div class="request-top">
                                    <div>
                                        <div class="request-title">{{ $request['barang_ringkas'] }} - {{ $request['jumlah_ringkas'] }} unit</div>
                                        <div class="request-subtitle">{{ $request['kode_permintaan'] }} • {{ $request['tanggal_label'] }}</div>
                                    </div>
                                    <span class="badge {{ $request['status_class'] }}">{{ $request['status'] }}</span>
                                </div>

                                <div class="request-grid">
                                    <div class="info-item">
                                        <div class="info-label">Kelas</div>
                                        <div class="info-value">{{ $request['ruangan'] }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Diajukan oleh</div>
                                        <div class="info-value">{{ $request['peminta'] }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Jenis</div>
                                        <div class="info-value">{{ $request['jenis'] }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Jumlah</div>
                                        <div class="info-value">{{ $request['jumlah_ringkas'] }} unit</div>
                                    </div>
                                </div>

                                <div class="reason-card">
                                    <div class="reason-title">Alasan</div>
                                    <div class="reason-copy">{{ $request['alasan'] }}</div>
                                </div>

                                @if (! empty($request['foto_kerusakan']))
                                    <div class="damage-photo-card">
                                        <div class="damage-photo-title">Foto Kerusakan</div>
                                        <button type="button" class="damage-photo-link js-photo-preview" data-photo-src="{{ asset('storage/'.$request['foto_kerusakan']) }}" data-photo-title="Foto kerusakan {{ $request['barang_ringkas'] }}">
                                            <img src="{{ asset('storage/'.$request['foto_kerusakan']) }}" alt="Foto kerusakan {{ $request['barang_ringkas'] }}" class="damage-photo-img">
                                        </button>
                                    </div>
                                @endif

                                <div class="flow-row">
                                    @foreach ($request['flow'] as $flow)
                                        <span class="flow-chip {{ $flow['status'] }}">
                                            @if ($flow['status'] === 'done')
                                                <i class="bi bi-check-circle-fill"></i>
                                            @elseif ($flow['status'] === 'current')
                                                <i class="bi bi-hourglass-split"></i>
                                            @elseif ($flow['status'] === 'rejected')
                                                <i class="bi bi-x-circle-fill"></i>
                                            @else
                                                <i class="bi bi-circle"></i>
                                            @endif
                                            {{ $flow['label'] }}
                                        </span>
                                    @endforeach
                                </div>

                                @if ($request['can_action'])
                                    <div class="actions-row">
                                        <form method="POST" action="{{ route('admin.requests.approve', $request['id_permintaan']) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-approve">
                                                <i class="bi bi-check2-circle"></i>
                                                Setujui
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-reject toggle-reject-form">
                                            <i class="bi bi-x-circle"></i>
                                            Tolak
                                        </button>
                                    </div>

                                    <form method="POST" action="{{ route('admin.requests.reject', $request['id_permintaan']) }}" class="reject-form">
                                        @csrf
                                        <label>Alasan penolakan</label>
                                        <textarea name="rejection_reason" placeholder="Tuliskan alasan penolakan agar ketua kelas memahami tindak lanjutnya...">{{ old('rejection_reason') }}</textarea>
                                        <div class="reject-actions">
                                            <button type="submit" class="btn btn-reject">
                                                <i class="bi bi-send-x-fill"></i>
                                                Kirim Penolakan
                                            </button>
                                            <button type="button" class="btn btn-cancel close-reject-form">Batal</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="status-note">
                                        Pengajuan ini sudah diproses. Untuk melihat keseluruhan riwayatnya, buka menu [Riwayat Pengajuan].
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </section>

                    @if ($requests->hasPages())
                        <div class="pagination-wrap">
                            {{ $requests->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </main>
    </div>
    @include('chatbot')

    <div class="photo-modal-backdrop" id="damagePhotoModal" aria-hidden="true">
        <section class="photo-modal" role="dialog" aria-modal="true" aria-label="Foto Kerusakan">
            <button type="button" class="photo-modal-close" id="damagePhotoClose" aria-label="Tutup foto">
                <i class="bi bi-x-lg"></i>
            </button>
            <img src="" alt="Foto kerusakan" class="photo-modal-img" id="damagePhotoImage">
        </section>
    </div>

    <script>
        (function () {
            const toggleButtons = document.querySelectorAll('.toggle-reject-form');
            const closeButtons = document.querySelectorAll('.close-reject-form');
            const photoModal = document.getElementById('damagePhotoModal');
            const photoImage = document.getElementById('damagePhotoImage');
            const photoClose = document.getElementById('damagePhotoClose');

            toggleButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const card = button.closest('.request-card');
                    const form = card ? card.querySelector('.reject-form') : null;
                    if (form) {
                        form.classList.add('active');
                    }
                });
            });

            closeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const form = button.closest('.reject-form');
                    if (form) {
                        form.classList.remove('active');
                    }
                });
            });

            function openPhotoModal(button) {
                if (!photoModal || !photoImage) {
                    return;
                }

                photoImage.src = button.dataset.photoSrc || '';
                photoImage.alt = button.dataset.photoTitle || 'Foto kerusakan';
                photoModal.classList.add('open');
                photoModal.setAttribute('aria-hidden', 'false');
            }

            function closePhotoModal() {
                if (!photoModal || !photoImage) {
                    return;
                }

                photoModal.classList.remove('open');
                photoModal.setAttribute('aria-hidden', 'true');
                photoImage.src = '';
            }

            document.querySelectorAll('.js-photo-preview').forEach(function (button) {
                button.addEventListener('click', function () {
                    openPhotoModal(button);
                });
            });

            photoClose?.addEventListener('click', closePhotoModal);
            photoModal?.addEventListener('click', function (event) {
                if (event.target === photoModal) {
                    closePhotoModal();
                }
            });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && photoModal?.classList.contains('open')) {
                    closePhotoModal();
                }
            });
        })();
    </script>
</body>
</html>

