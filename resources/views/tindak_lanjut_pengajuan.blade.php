<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tindak Lanjut Pengajuan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/tindak_lanjut_pengajuan.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="superadmin-realization-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengelola Sistem' }}</div>
                    <h1 class="hero-title">Tindak Lanjut Pengajuan</h1>
                    <p class="hero-subtitle">Lanjutkan pengajuan yang sudah disetujui kepala sekolah menjadi inventaris nyata. Di tahap ini superadmin mengeksekusi, bukan melakukan approval lagi.</p>
                </section>

                <section class="summary-grid">
                    <article class="summary-card">
                        <div class="summary-label">Menunggu Pengajuan</div>
                        <div class="summary-value">{{ number_format($summary['waiting']) }}</div>
                        <div class="summary-note">Pengajuan yang sudah approved owner dan siap dieksekusi.</div>
                    </article>
                    <article class="summary-card is-accent">
                        <div class="summary-label">Sudah Di Ajuin</div>
                        <div class="summary-value">{{ number_format($summary['realized']) }}</div>
                        <div class="summary-note">Pengajuan yang sudah masuk ke inventaris.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Ditolak</div>
                        <div class="summary-value">{{ number_format($summary['rejected']) }}</div>
                        <div class="summary-note">Pengajuan yang ditolak di tahap kepala sekolah.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Total Pengajuan</div>
                        <div class="summary-value">{{ number_format($summary['total']) }}</div>
                        <div class="summary-note">Seluruh pengajuan yang masuk ke tahap tindak lanjut.</div>
                    </article>
                </section>

                <section class="filter-card">
                    <form method="GET" action="{{ route('superadmin.requests.realization') }}" class="filter-form">
                        <div class="filter-field">
                            <label for="searchRequest">Search Nama Barang</label>
                            <input id="searchRequest" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama barang, pengaju, atau ruangan">
                        </div>
                        <div class="filter-field">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter" name="status">
                                <option value="menunggu" @selected($filters['status'] === 'menunggu')>Menunggu Realisasi</option>
                                <option value="selesai" @selected($filters['status'] === 'selesai')>Sudah Direalisasi</option>
                                <option value="ditolak" @selected($filters['status'] === 'ditolak')>Ditolak</option>
                                <option value="semua" @selected($filters['status'] === 'semua')>Semua Status</option>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label for="roomFilter">Ruangan</label>
                            <select id="roomFilter" name="room">
                                <option value="semua" @selected($filters['room'] === 'semua')>Semua Ruangan</option>
                                @foreach ($roomOptions as $option)
                                    <option value="{{ $option->id_ruangan }}" @selected($filters['room'] === (string) $option->id_ruangan)>{{ $option->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label for="dateFilter">Tanggal</label>
                            <input id="dateFilter" type="date" name="date" value="{{ $filters['date'] }}">
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="filter-btn">
                                <i class="bi bi-funnel-fill"></i>
                                <span>Terapkan</span>
                            </button>
                            <a href="{{ route('superadmin.requests.realization') }}" class="filter-link">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </section>

                @if (session('success') || session('error') || $errors->any())
                    <div class="feedback-stack">
                        @if (session('success'))
                            <div class="success-banner">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="error-banner">{{ session('error') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="error-banner">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <section class="table-card">
                    <div class="table-header">
                        <div>
                            <div class="table-title">Daftar Tindak Lanjut Pengajuan</div>
                            <div class="table-subtitle">Menampilkan {{ number_format($requests->total()) }} pengajuan berdasarkan filter aktif.</div>
                        </div>
                    </div>

                    @if (count($requestRows) === 0)
                        <div class="empty-state">Belum ada pengajuan yang cocok dengan filter saat ini.</div>
                    @else
                        <div class="table-wrap">
                            <table class="mobile-card-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengaju</th>
                                        <th>Ruangan</th>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Status Approval</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status Realisasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requestRows as $index => $requestRow)
                                        <tr>
                                            <td data-label="No">{{ ($requests->firstItem() ?? 1) + $index }}</td>
                                            <td data-label="Nama Pengaju">
                                                <div class="primary-text">{{ $requestRow['pengaju'] }}</div>
                                                <div class="muted-text">{{ $requestRow['kode_permintaan'] }}</div>
                                            </td>
                                            <td data-label="Ruangan">
                                                <div class="primary-text">{{ $requestRow['ruangan'] }}</div>
                                                <div class="muted-text">{{ $requestRow['kode_ruangan'] }}</div>
                                            </td>
                                            <td data-label="Barang">{{ $requestRow['barang'] !== '' ? $requestRow['barang'] : '-' }}</td>
                                            <td data-label="Jumlah">{{ number_format($requestRow['jumlah']) }} item</td>
                                            <td data-label="Status Approval"><span class="pill {{ $requestRow['approval_class'] }}">{{ $requestRow['approval_label'] }}</span></td>
                                            <td data-label="Tanggal Pengajuan">{{ $requestRow['tanggal_label'] }}</td>
                                            <td data-label="Status Realisasi"><span class="pill {{ $requestRow['realisasi_class'] }}">{{ $requestRow['realisasi_label'] }}</span></td>
                                            <td data-label="Aksi">
                                                <div class="action-group">
                                                    <button type="button" class="row-action js-open-modal" data-modal="detail-request-{{ $requestRow['id_permintaan'] }}">
                                                        <i class="bi bi-eye"></i>
                                                        <span>Detail</span>
                                                    </button>
                                                    @if ($requestRow['can_realize'])
                                                        <button type="button" class="row-action info js-open-modal" data-modal="realize-request-{{ $requestRow['id_permintaan'] }}">
                                                            <i class="bi bi-box-seam"></i>
                                                            <span>Realisasikan</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($requests->hasPages())
                            <div class="pagination-wrap">
                                <div class="pagination">
                                    <span class="pagination-info">Halaman {{ $requests->currentPage() }} dari {{ $requests->lastPage() }}</span>

                                    @if ($requests->onFirstPage())
                                        <span class="pagination-link disabled"><i class="bi bi-chevron-left"></i></span>
                                    @else
                                        <a href="{{ $requests->previousPageUrl() }}" class="pagination-link" aria-label="Halaman sebelumnya">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    @endif

                                    @foreach ($requests->getUrlRange(1, $requests->lastPage()) as $page => $url)
                                        @if ($page === $requests->currentPage())
                                            <span class="pagination-current">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if ($requests->hasMorePages())
                                        <a href="{{ $requests->nextPageUrl() }}" class="pagination-link" aria-label="Halaman berikutnya">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    @else
                                        <span class="pagination-link disabled"><i class="bi bi-chevron-right"></i></span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                </section>
            </div>
        </main>
    </div>

    @foreach ($requestRows as $requestRow)
        <div class="modal-shell" id="modal-detail-request-{{ $requestRow['id_permintaan'] }}" aria-hidden="true">
            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="detailRequestTitle-{{ $requestRow['id_permintaan'] }}">
                <div class="modal-header">
                    <div>
                        <div class="modal-title" id="detailRequestTitle-{{ $requestRow['id_permintaan'] }}">Detail Pengajuan</div>
                        <div class="modal-subtitle">Ringkasan approval dan tindak lanjut untuk pengajuan {{ $requestRow['kode_permintaan'] }}.</div>
                    </div>
                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="detail-grid">
                    <div class="detail-card">
                        <div class="detail-label">Nama Pengaju</div>
                        <div class="detail-value">{{ $requestRow['pengaju'] }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Ruangan</div>
                        <div class="detail-value">{{ $requestRow['ruangan'] }}</div>
                        <div class="detail-value muted">{{ $requestRow['kode_ruangan'] }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Status Approval</div>
                        <div class="detail-value">{{ $requestRow['approval_label'] }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Status Realisasi</div>
                        <div class="detail-value">{{ $requestRow['realisasi_label'] }}</div>
                        <div class="detail-value muted">
                            @if ($requestRow['realized_at'])
                                Direalisasikan pada {{ $requestRow['realized_at'] }}
                            @else
                                Belum ada riwayat realisasi tersimpan.
                            @endif
                        </div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Tanggal Pengajuan</div>
                        <div class="detail-value">{{ $requestRow['tanggal_label'] }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Sumber</div>
                        <div class="detail-value">{{ $requestRow['source'] }}</div>
                    </div>
                </div>

                <div class="realization-list realization-list-spaced">
                    @foreach ($requestRow['details'] as $detail)
                        <div class="realization-row">
                            <div class="realization-row-title">{{ $detail['nama_barang'] }}</div>
                            <div class="muted-text">
                                Diminta: {{ number_format($detail['jumlah_diminta']) }} item |
                                Disetujui: {{ number_format($detail['jumlah_disetujui']) }} item |
                                Direalisasi: {{ number_format($detail['jumlah_diberikan']) }} item
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if ($requestRow['can_realize'])
            <div class="modal-shell" id="modal-realize-request-{{ $requestRow['id_permintaan'] }}" aria-hidden="true">
                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="realizeRequestTitle-{{ $requestRow['id_permintaan'] }}">
                    <div class="modal-header">
                        <div>
                            <div class="modal-title" id="realizeRequestTitle-{{ $requestRow['id_permintaan'] }}">Realisasikan Pengajuan</div>
                            <div class="modal-subtitle">Barang akan ditambahkan ke inventaris ruangan sebagai kondisi awal baik dengan sumber pengajuan.</div>
                        </div>
                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('superadmin.requests.realization.store', $requestRow['id_permintaan']) }}" class="modal-form">
                        @csrf
                        <input type="hidden" name="status_filter" value="{{ $filters['status'] }}">
                        <input type="hidden" name="room_filter" value="{{ $filters['room'] }}">
                        <input type="hidden" name="date_filter" value="{{ $filters['date'] }}">
                        <input type="hidden" name="q" value="{{ $filters['q'] }}">

                        <div class="field-grid">
                            <div class="field-group">
                                <label>Ruangan</label>
                                <input type="text" value="{{ $requestRow['ruangan'] }}" readonly>
                            </div>
                            <div class="field-group">
                                <label for="realizationDate-{{ $requestRow['id_permintaan'] }}">Tanggal Realisasi</label>
                                <input id="realizationDate-{{ $requestRow['id_permintaan'] }}" type="date" name="tanggal_realisasi" value="{{ old('tanggal_realisasi', now()->toDateString()) }}" required>
                            </div>
                        </div>

                        <div class="realization-list">
                            @foreach ($requestRow['details'] as $detail)
                                <div class="realization-row">
                                    <div class="realization-row-title">{{ $detail['nama_barang'] }}</div>
                                    <div class="muted-text">Jumlah disetujui/diminta: {{ number_format($detail['jumlah_disetujui']) }} item | Kondisi awal: Baik | Sumber: Pengajuan</div>
                                    <div class="field-group field-group-spaced">
                                        <label for="qty-{{ $requestRow['id_permintaan'] }}-{{ $detail['id_detail_permintaan'] }}">Jumlah Direalisasikan</label>
                                        <input
                                            id="qty-{{ $requestRow['id_permintaan'] }}-{{ $detail['id_detail_permintaan'] }}"
                                            type="number"
                                            min="0"
                                            max="{{ $detail['jumlah_disetujui'] }}"
                                            name="qty_{{ $detail['id_detail_permintaan'] }}"
                                            value="{{ old('qty_'.$detail['id_detail_permintaan'], $detail['jumlah_disetujui']) }}"
                                            required
                                        >
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="modal-actions">
                            <button type="button" class="ghost-btn js-close-modal">Batal</button>
                            <button type="submit" class="submit-btn">
                                <i class="bi bi-check-circle"></i>
                                <span>Simpan Realisasi</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    <script>
        (function () {
            const modalShells = document.querySelectorAll('.modal-shell');
            const openButtons = document.querySelectorAll('.js-open-modal');
            const closeButtons = document.querySelectorAll('.js-close-modal');

            function closeAllModals() {
                modalShells.forEach((modal) => {
                    modal.classList.remove('is-open');
                    modal.setAttribute('aria-hidden', 'true');
                });
                window.InfraSPHScrollLock?.unlock();
                document.body.style.overflow = '';
            }

            function openModal(modalName) {
                const target = document.getElementById('modal-' + modalName);
                if (!target) {
                    return;
                }

                closeAllModals();
                target.classList.add('is-open');
                target.setAttribute('aria-hidden', 'false');
                window.InfraSPHScrollLock?.lock();
                document.body.style.overflow = 'hidden';
            }

            openButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    openModal(this.dataset.modal);
                });
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', closeAllModals);
            });

            modalShells.forEach((modal) => {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeAllModals();
                    }
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeAllModals();
                }
            });

            @if (session('modal'))
                openModal(@json(session('modal')));
            @endif
        })();
    </script>
</body>
</html>

