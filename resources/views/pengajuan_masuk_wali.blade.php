<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengajuan Kelas | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --brand-orange: #ff5900;
            --text-dark: #1f2937;
            --page-bg: #fff8f4;
            --panel-border: #f3e3db;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
        }

        .inbox-page {
            margin-left: 320px;
            width: calc(100% - 320px);
            min-height: 100vh;
            padding: 2rem 1.6rem 2.5rem;
            transition: margin-left 0.28s ease, width 0.28s ease, padding 0.28s ease;
        }

        .app-shell.sidebar-collapsed .inbox-page {
            margin-left: 88px;
            width: calc(100% - 88px);
        }

        .page-shell { width: 100%; max-width: none; }

        .hero-card,
        .request-card,
        .empty-card,
        .success-banner,
        .error-banner {
            background: #ffffff;
            border: 1px solid var(--panel-border);
            border-radius: 28px;
            box-shadow: 0 18px 38px -28px rgba(31, 41, 55, 0.24);
        }

        .hero-card {
            padding: 1.6rem 1.7rem;
            margin-bottom: 1.2rem;
            background: linear-gradient(135deg, rgba(255, 89, 0, 0.08), rgba(255, 89, 0, 0.02));
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(255, 89, 0, 0.12);
            color: var(--brand-orange);
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 0.95rem;
        }

        .hero-title {
            font-size: clamp(1.8rem, 2.6vw, 2.4rem);
            color: var(--brand-orange);
            margin-bottom: 0.7rem;
            letter-spacing: -0.04em;
        }

        .hero-subtitle {
            color: #5b6472;
            line-height: 1.7;
            max-width: 760px;
        }

        .feedback-stack {
            display: grid;
            gap: 0.85rem;
            margin-bottom: 1rem;
        }

        .success-banner,
        .error-banner {
            padding: 1rem 1.1rem;
            border-radius: 22px;
        }

        .success-banner {
            background: #f3fff5;
            border-color: #cdeed5;
            color: #166534;
        }

        .error-banner {
            background: #fff7f4;
            border-color: #ffd7c7;
            color: #c2410c;
        }

        .request-stack {
            display: grid;
            gap: 1rem;
        }

        .pagination-wrap {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .pagination-wrap nav > div:first-child {
            display: none;
        }

        .pagination-wrap svg {
            width: 1rem;
            height: 1rem;
        }

        .pagination-wrap nav > div:last-child > span,
        .pagination-wrap nav > div:last-child a {
            border-radius: 14px;
            border: 1px solid #ead9d0;
            padding: 0.7rem 0.9rem;
            color: #344054;
            text-decoration: none;
            background: #ffffff;
            font-weight: 700;
        }

        .pagination-wrap nav > div:last-child > span[aria-current="page"] {
            background: linear-gradient(135deg, var(--brand-orange), #ff7b2f);
            border-color: transparent;
            color: #ffffff;
        }

        .request-card {
            padding: 1.2rem 1.2rem 1.1rem;
        }

        .request-top {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .request-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #172033;
            margin-bottom: 0.15rem;
        }

        .request-subtitle {
            color: #7b8794;
            font-size: 0.88rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.45rem 0.8rem;
            font-size: 0.78rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge.process { background: #fff6db; color: #a16207; }
        .badge.approved { background: #eaf8ef; color: #15803d; }
        .badge.rejected { background: #fff0eb; color: #c2410c; }

        .request-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.9rem;
            margin-bottom: 1rem;
        }

        .info-item {
            padding: 0.85rem 0.9rem;
            border-radius: 18px;
            background: #fff8f4;
            border: 1px solid #f6e4da;
        }

        .info-label {
            color: #6b7280;
            font-size: 0.78rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            color: #172033;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.5;
        }

        .reason-card {
            padding: 0.95rem 1rem;
            border-radius: 20px;
            background: #fffdfb;
            border: 1px solid #f3e3db;
            margin-bottom: 1rem;
        }

        .reason-title {
            font-size: 0.82rem;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .reason-copy {
            color: #4b5563;
            line-height: 1.7;
        }

        .damage-photo-card {
            padding: 0.95rem 1rem;
            border-radius: 20px;
            background: #fffdfb;
            border: 1px solid #f3e3db;
            margin-bottom: 1rem;
        }

        .damage-photo-title {
            font-size: 0.82rem;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .damage-photo-link {
            display: inline-block;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #f6d7c5;
            background: #fff8f4;
            cursor: zoom-in;
        }

        .damage-photo-img {
            display: block;
            width: min(100%, 360px);
            max-height: 240px;
            object-fit: cover;
        }

        .photo-modal-backdrop {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.2rem;
            background: rgba(15, 23, 42, 0.62);
            z-index: 1600;
        }

        .photo-modal-backdrop.open {
            display: flex;
        }

        .photo-modal {
            position: relative;
            width: auto;
            max-width: min(94vw, 1100px);
            max-height: 90vh;
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
        }

        .photo-modal-close {
            position: absolute;
            top: -16px;
            right: -16px;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.92);
            color: var(--brand-orange);
            cursor: pointer;
            box-shadow: 0 18px 38px -20px rgba(15, 23, 42, 0.55);
            z-index: 1;
        }

        .photo-modal-img {
            display: block;
            max-width: min(94vw, 1100px);
            max-height: 86vh;
            object-fit: contain;
            border-radius: 12px;
            background: transparent;
            box-shadow: 0 28px 72px -32px rgba(15, 23, 42, 0.85);
        }

        .flow-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-bottom: 1rem;
        }

        .flow-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 0.8rem;
            border-radius: 999px;
            border: 1px solid #ead9d0;
            background: #ffffff;
            color: #667085;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .flow-chip.done {
            background: #eaf8ef;
            border-color: #cdeed5;
            color: #15803d;
        }

        .flow-chip.current {
            background: #fff6db;
            border-color: #f2dfa7;
            color: #a16207;
        }

        .flow-chip.rejected {
            background: #fff0eb;
            border-color: #ffd7c7;
            color: #c2410c;
        }

        .actions-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.85rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            border: none;
            border-radius: 16px;
            padding: 0.85rem 1rem;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-approve {
            background: linear-gradient(135deg, var(--brand-orange), #ff7b2f);
            color: #ffffff;
        }

        .btn-reject {
            background: #ffffff;
            color: #c2410c;
            border: 1px solid #ffd7c7;
        }

        .status-note {
            color: #667085;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .reject-form {
            display: none;
            gap: 0.7rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed #f0d7ca;
        }

        .reject-form.active {
            display: grid;
        }

        .reject-form label {
            font-weight: 700;
            color: #344054;
        }

        .reject-form textarea {
            width: 100%;
            min-height: 110px;
            border: 1px solid #ead9d0;
            border-radius: 18px;
            padding: 0.9rem 0.95rem;
            font: inherit;
            color: #172033;
            background: #fffefd;
            resize: vertical;
        }

        .reject-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-cancel {
            background: #ffffff;
            color: #344054;
            border: 1px solid #ead9d0;
        }

        .empty-card {
            padding: 2rem 1.3rem;
            text-align: center;
        }

        .empty-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #172033;
            margin-bottom: 0.4rem;
        }

        .empty-copy {
            color: #667085;
            line-height: 1.7;
        }

        @media (max-width: 1180px) {
            .request-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 860px) {
            .inbox-page {
                margin-left: 0;
                width: 100%;
                padding: 1.2rem 1rem 2rem;
            }

            .request-top,
            .request-grid {
                grid-template-columns: 1fr;
                display: grid;
            }
        }
    </style>
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
