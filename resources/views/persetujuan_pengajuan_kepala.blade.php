<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Persetujuan Pengajuan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/persetujuan_pengajuan_kepala.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="owner-approval-page">
            <div class="page-shell">
            <section class="hero-card">
                <div class="eyebrow">Kepala Sekolah</div>
                <h1 class="hero-title">Persetujuan Pengajuan</h1>
                <p class="hero-subtitle">Tinjau dan berikan keputusan atas pengajuan dari seluruh kelas yang sudah disetujui wali kelas.</p>
            </section>

            <section class="summary-grid">
                <article class="summary-card is-accent">
                    <div class="summary-label">Menunggu Persetujuan</div>
                    <div class="summary-value">{{ number_format($summary['waiting']) }}</div>
                    <div class="summary-note">Pengajuan yang masih menunggu keputusan kepala sekolah</div>
                </article>
                <article class="summary-card">
                    <div class="summary-label">Disetujui Hari Ini</div>
                    <div class="summary-value">{{ number_format($summary['approved_today']) }}</div>
                    <div class="summary-note">Pengajuan yang disetujui kepala sekolah hari ini</div>
                </article>
                <article class="summary-card">
                    <div class="summary-label">Ditolak Hari Ini</div>
                    <div class="summary-value">{{ number_format($summary['rejected_today']) }}</div>
                    <div class="summary-note">Pengajuan yang ditolak kepala sekolah hari ini</div>
                </article>
            </section>

            <section class="filter-card">
                <div class="filter-row">
                    <a href="{{ route('owner.requests.approval', ['status' => 'menunggu']) }}" @class(['filter-pill', 'active' => $activeStatus === 'menunggu'])>Menunggu</a>
                    <a href="{{ route('owner.requests.approval', ['status' => 'disetujui']) }}" @class(['filter-pill', 'active' => $activeStatus === 'disetujui'])>Disetujui</a>
                    <a href="{{ route('owner.requests.approval', ['status' => 'ditolak']) }}" @class(['filter-pill', 'active' => $activeStatus === 'ditolak'])>Ditolak</a>
                </div>
            </section>

            @if (session('success') || $errors->any())
                <div class="feedback-stack">
                    @if (session('success'))
                        <div class="success-banner">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="error-banner">
                            {{ $errors->first() }}
                        </div>
                    @endif
                </div>
            @endif

            @if ($requests->isEmpty())
                <section class="empty-card">Tidak ada pengajuan yang perlu disetujui saat ini.</section>
            @else
                <section class="request-stack">
                    @foreach ($requests as $requestItem)
                        <article class="request-card">
                            <div class="request-top">
                                <div>
                                    <div class="request-title">{{ $requestItem['barang_ringkas'] }} - {{ $requestItem['jumlah_ringkas'] }} unit</div>
                                    <div class="request-subtitle">{{ $requestItem['ruangan'] }} | {{ $requestItem['tanggal_label'] }}</div>
                                </div>
                                <span class="badge {{ $requestItem['status_class'] }}">{{ $requestItem['status'] }}</span>
                            </div>

                            <div class="request-grid">
                                <div class="info-item">
                                    <div class="info-label">Jenis</div>
                                    <div class="info-value">{{ $requestItem['jenis'] }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Kelas</div>
                                    <div class="info-value">{{ $requestItem['ruangan'] }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Diajukan Oleh</div>
                                    <div class="info-value">{{ $requestItem['peminta'] }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Status Wali Kelas</div>
                                    <div class="info-value">{{ $requestItem['wali_status'] }}</div>
                                </div>
                            </div>

                            <div class="reason-card">
                                <div class="reason-title">Alasan Pengajuan</div>
                                <div class="reason-copy">{{ $requestItem['alasan'] }}</div>
                            </div>

                            <div class="flow-row">
                                @foreach ($requestItem['flow'] as $flow)
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

                            @if ($requestItem['can_action'])
                                <div class="actions-row">
                                    <form method="POST" action="{{ route('owner.requests.approve', $requestItem['id_permintaan']) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-approve">
                                            <i class="bi bi-check2-circle"></i>
                                            Setujui
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-reject js-reject-toggle" data-target="reject-form-{{ $requestItem['id_permintaan'] }}">
                                        <i class="bi bi-x-circle"></i>
                                        Tolak
                                    </button>
                                </div>

                                <form method="POST" action="{{ route('owner.requests.reject', $requestItem['id_permintaan']) }}" class="reject-form" id="reject-form-{{ $requestItem['id_permintaan'] }}">
                                    @csrf
                                    <textarea name="rejection_reason" placeholder="Tuliskan alasan penolakan..." required></textarea>
                                    <div class="reject-form-actions">
                                        <button type="button" class="btn btn-cancel js-reject-cancel" data-target="reject-form-{{ $requestItem['id_permintaan'] }}">Batal</button>
                                        <button type="submit" class="btn btn-submit-reject">Kirim</button>
                                    </div>
                                </form>
                            @else
                                <div class="status-note">
                                    @if ($requestItem['status_key'] === 'disetujui')
                                        Pengajuan ini sudah disetujui kepala sekolah.
                                    @elseif ($requestItem['status_key'] === 'ditolak')
                                        Pengajuan ini sudah ditolak pada tahap kepala sekolah.
                                    @endif
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

        @include('chatbot')
    </div>

    <script>
        document.querySelectorAll('.js-reject-toggle').forEach((button) => {
            button.addEventListener('click', () => {
                const target = document.getElementById(button.dataset.target);
                if (!target) {
                    return;
                }

                target.classList.add('active');
            });
        });

        document.querySelectorAll('.js-reject-cancel').forEach((button) => {
            button.addEventListener('click', () => {
                const target = document.getElementById(button.dataset.target);
                if (!target) {
                    return;
                }

                target.classList.remove('active');
            });
        });
    </script>
</body>
</html>

