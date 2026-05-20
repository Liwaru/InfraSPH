<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Catatan Aktivitas | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/activity_logs.css') }}">
</head>
<body>
<div class="app-shell" id="appShell">
    @include('header')

    <main class="activity-page">
        <div class="page-shell">
            <section class="hero-card">
                <div class="eyebrow"><i class="bi bi-activity"></i> Catatan Aktivitas</div>
                <h1 class="hero-title">Pantau semua aktivitas sistem.</h1>
                <p class="hero-subtitle">Lihat siapa melakukan apa, kapan aktivitas terjadi, modul yang terdampak, target data, dan konteks ruangan yang berkaitan.</p>
            </section>

            <section class="summary-grid">
                <div class="summary-card"><div class="summary-label">Total Aktivitas</div><div class="summary-value">{{ number_format($summary['total']) }}</div></div>
                <div class="summary-card"><div class="summary-label">Aktivitas Hari Ini</div><div class="summary-value">{{ number_format($summary['today']) }}</div></div>
                <div class="summary-card"><div class="summary-label">Login / Logout</div><div class="summary-value">{{ number_format($summary['login']) }}</div></div>
                <div class="summary-card"><div class="summary-label">Perubahan Data</div><div class="summary-value">{{ number_format($summary['data_changes']) }}</div></div>
            </section>

            <nav class="view-tabs" aria-label="Pilihan catatan">
                <a href="{{ route('activity.logs', array_merge(request()->except(['aktivitas_page', 'data_page']), ['tab' => 'aktivitas'])) }}" @class(['view-tab', 'active' => $filters['tab'] === 'aktivitas'])>
                    <i class="bi bi-activity"></i>
                    Aktivitas
                </a>
                <a href="{{ route('activity.logs', array_merge(request()->except(['aktivitas_page', 'data_page']), ['tab' => 'data'])) }}" @class(['view-tab', 'active' => $filters['tab'] === 'data'])>
                    <i class="bi bi-database"></i>
                    Data
                </a>
            </nav>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif

            <section class="filter-card">
                <form method="GET" action="{{ route('activity.logs') }}" class="filter-form">
                    <input type="hidden" name="tab" value="{{ $filters['tab'] }}">
                    <div>
                        <label for="name">Nama User</label>
                        <input id="name" type="text" name="name" value="{{ $filters['name'] }}" placeholder="Cari nama user">
                    </div>
                    <div>
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <option value="">Semua Role</option>
                            @foreach ($roles as $level => $role)
                                <option value="{{ $level }}" @selected($filters['role'] === (string) $level)>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_start">Tanggal Awal</label>
                        <input id="date_start" type="date" name="date_start" value="{{ $filters['date_start'] }}">
                    </div>
                    <div>
                        <label for="date_end">Tanggal Akhir</label>
                        <input id="date_end" type="date" name="date_end" value="{{ $filters['date_end'] }}">
                    </div>
                    <button type="submit" class="btn"><i class="bi bi-search"></i> Cari</button>
                    <a href="{{ route('activity.logs', ['tab' => $filters['tab']]) }}" class="btn secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                </form>
            </section>

            <section class="table-card">
                <div class="table-head">
                    <div>
                        <div class="table-title">{{ $filters['tab'] === 'data' ? 'Daftar Data CRUD' : 'Daftar Aktivitas' }}</div>
                        <div class="table-subtitle">{{ $filters['tab'] === 'data' ? 'Data yang dibuat, diubah, dihapus, atau diproses tampil paling atas.' : 'Aktivitas terbaru tampil paling atas.' }}</div>
                    </div>
                </div>

                @if (! $activityTableReady)
                    <div class="empty-state">Tabel activity_logs belum tersedia. Jalankan migrasi database agar catatan aktivitas bisa mulai tersimpan.</div>
                @elseif ($filters['tab'] === 'aktivitas' && $logs->isEmpty())
                    <div class="empty-state">Belum ada aktivitas yang cocok dengan filter saat ini.</div>
                @elseif ($filters['tab'] === 'aktivitas')
                    <div class="table-scroll">
                        <table class="mobile-card-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Nama User</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                    <th>Modul</th>
                                    <th>Target / Data</th>
                                    <th>Detail Aktivitas</th>
                                    <th>Kelas / Ruangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    @php
                                        $actionClass = match ($log->action) {
                                            'Menambah' => 'action-create',
                                            'Mengubah' => 'action-update',
                                            'Menghapus' => 'action-delete',
                                            'Menyetujui' => 'action-approve',
                                            'Menolak' => 'action-reject',
                                            'Merealisasikan' => 'action-realize',
                                            'Memulihkan' => 'action-realize',
                                            'Login' => 'action-login',
                                            'Logout' => 'action-logout',
                                            default => 'action-update',
                                        };
                                    @endphp
                                    <tr>
                                        <td data-label="No">{{ $logs->firstItem() + $loop->index }}</td>
                                        <td data-label="Tanggal & Waktu">
                                            <div class="cell-strong">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                            <div class="cell-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</div>
                                        </td>
                                        <td data-label="Nama User" class="cell-strong">{{ $log->user_name ?? '-' }}</td>
                                        <td data-label="Role"><span class="badge role-{{ $log->user_level ?? 0 }}">{{ $log->role_name ?? '-' }}</span></td>
                                        <td data-label="Aksi"><span class="badge {{ $actionClass }}">{{ $log->action }}</span></td>
                                        <td data-label="Modul">{{ $log->module }}</td>
                                        <td data-label="Target / Data">{{ $log->target ?? '-' }}</td>
                                        <td data-label="Detail Aktivitas">{{ $log->detail ?? '-' }}</td>
                                        <td data-label="Kelas / Ruangan">{{ $log->room_context ?? '-' }}</td>
                                        <td data-label="Detail">
                                            <div class="row-actions">
                                                <button
                                                    type="button"
                                                    class="detail-btn"
                                                    data-detail-time="{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}"
                                                    data-detail-user="{{ $log->user_name ?? '-' }}"
                                                    data-detail-role="{{ $log->role_name ?? '-' }}"
                                                    data-detail-action="{{ $log->action }}"
                                                    data-detail-module="{{ $log->module }}"
                                                    data-detail-target="{{ $log->target ?? '-' }}"
                                                    data-detail-room="{{ $log->room_context ?? '-' }}"
                                                    data-detail-text="{{ $log->detail ?? 'Tidak ada detail tambahan.' }}"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                    Detail
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">
                        <div>Menampilkan {{ $logs->firstItem() }} sampai {{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas</div>
                        <div class="pagination-links">
                            @if ($logs->onFirstPage())
                                <span class="page-disabled">Prev</span>
                            @else
                                <a class="page-link" href="{{ $logs->previousPageUrl() }}">Prev</a>
                            @endif
                            @for ($page = 1; $page <= $logs->lastPage(); $page++)
                                @if ($page === $logs->currentPage())
                                    <span class="page-current">{{ $page }}</span>
                                @else
                                    <a class="page-link" href="{{ $logs->url($page) }}">{{ $page }}</a>
                                @endif
                            @endfor
                            @if ($logs->hasMorePages())
                                <a class="page-link" href="{{ $logs->nextPageUrl() }}">Next</a>
                            @else
                                <span class="page-disabled">Next</span>
                            @endif
                        </div>
                    </div>
                @elseif ($dataLogs->isEmpty())
                    <div class="empty-state">Belum ada data CRUD yang cocok dengan filter saat ini.</div>
                @else
                    <div class="table-scroll">
                        <table class="mobile-card-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Nama User</th>
                                    <th>Role</th>
                                    <th>Aksi CRUD</th>
                                    <th>Modul</th>
                                    <th>Target / Data</th>
                                    <th>Detail Data</th>
                                    <th>Kelas / Ruangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataLogs as $log)
                                    @php
                                        $actionClass = match ($log->action) {
                                            'Menambah' => 'action-create',
                                            'Mengubah' => 'action-update',
                                            'Menghapus' => 'action-delete',
                                            'Menyetujui' => 'action-approve',
                                            'Menolak' => 'action-reject',
                                            'Merealisasikan', 'Memulihkan' => 'action-realize',
                                            default => 'action-update',
                                        };
                                        $canRestore = $dataArchiveReady
                                            && in_array($log->action, ['Menghapus', 'Mengubah'], true)
                                            && ! empty($log->archive_id)
                                            && empty($log->archive_restored_at)
                                            && (int) ($user['level'] ?? 0) === 3;
                                    @endphp
                                    <tr>
                                        <td data-label="No">{{ $dataLogs->firstItem() + $loop->index }}</td>
                                        <td data-label="Tanggal & Waktu">
                                            <div class="cell-strong">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                                            <div class="cell-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</div>
                                        </td>
                                        <td data-label="Nama User" class="cell-strong">{{ $log->user_name ?? '-' }}</td>
                                        <td data-label="Role"><span class="badge role-{{ $log->user_level ?? 0 }}">{{ $log->role_name ?? '-' }}</span></td>
                                        <td data-label="Aksi CRUD"><span class="badge {{ $actionClass }}">{{ $log->action }}</span></td>
                                        <td data-label="Modul">{{ $log->module }}</td>
                                        <td data-label="Target / Data">{{ $log->target ?? '-' }}</td>
                                        <td data-label="Detail Data">{{ $log->detail ?? '-' }}</td>
                                        <td data-label="Kelas / Ruangan">{{ $log->room_context ?? '-' }}</td>
                                        <td data-label="Aksi">
                                            @if ($canRestore)
                                                <form method="POST" action="{{ route('activity.data.restore', $log->archive_id) }}" onsubmit="return confirm('Pulihkan data ini?');">
                                                    @csrf
                                                    <button type="submit" class="restore-btn">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                        Pulihkan
                                                    </button>
                                                </form>
                                            @elseif (in_array($log->action, ['Menghapus', 'Mengubah'], true) && ! empty($log->archive_restored_at))
                                                <span class="badge action-realize">Sudah dipulihkan</span>
                                            @elseif (in_array($log->action, ['Menghapus', 'Mengubah'], true))
                                                <span class="badge action-update" title="Arsip tersedia hanya untuk perubahan setelah fitur ini aktif.">Tidak ada arsip</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrap">
                        <div>Menampilkan {{ $dataLogs->firstItem() }} sampai {{ $dataLogs->lastItem() }} dari {{ $dataLogs->total() }} data</div>
                        <div class="pagination-links">
                            @if ($dataLogs->onFirstPage())
                                <span class="page-disabled">Prev</span>
                            @else
                                <a class="page-link" href="{{ $dataLogs->previousPageUrl() }}">Prev</a>
                            @endif
                            @for ($page = 1; $page <= $dataLogs->lastPage(); $page++)
                                @if ($page === $dataLogs->currentPage())
                                    <span class="page-current">{{ $page }}</span>
                                @else
                                    <a class="page-link" href="{{ $dataLogs->url($page) }}">{{ $page }}</a>
                                @endif
                            @endfor
                            @if ($dataLogs->hasMorePages())
                                <a class="page-link" href="{{ $dataLogs->nextPageUrl() }}">Next</a>
                            @else
                                <span class="page-disabled">Next</span>
                            @endif
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </main>

    <div class="modal-backdrop" id="activityDetailModal" aria-hidden="true">
        <section class="detail-modal" role="dialog" aria-modal="true" aria-labelledby="activityDetailTitle">
            <div class="modal-head">
                <div class="modal-title" id="activityDetailTitle">Detail Aktivitas</div>
                <button type="button" class="modal-close" id="activityDetailClose" aria-label="Tutup detail">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Waktu</div>
                    <div class="detail-value" id="detailTime">-</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">User</div>
                    <div class="detail-value" id="detailUser">-</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Role</div>
                    <div class="detail-value" id="detailRole">-</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Aksi & Modul</div>
                    <div class="detail-value" id="detailActionModule">-</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Target / Data</div>
                    <div class="detail-value" id="detailTarget">-</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Kelas / Ruangan</div>
                    <div class="detail-value" id="detailRoom">-</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Detail Aktivitas</div>
                    <div class="detail-value" id="detailText">-</div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    (function () {
        const modal = document.getElementById('activityDetailModal');
        const closeButton = document.getElementById('activityDetailClose');
        const fields = {
            time: document.getElementById('detailTime'),
            user: document.getElementById('detailUser'),
            role: document.getElementById('detailRole'),
            actionModule: document.getElementById('detailActionModule'),
            target: document.getElementById('detailTarget'),
            room: document.getElementById('detailRoom'),
            text: document.getElementById('detailText'),
        };

        function openModal(button) {
            fields.time.textContent = button.dataset.detailTime || '-';
            fields.user.textContent = button.dataset.detailUser || '-';
            fields.role.textContent = button.dataset.detailRole || '-';
            fields.actionModule.textContent = (button.dataset.detailAction || '-') + ' - ' + (button.dataset.detailModule || '-');
            fields.target.textContent = button.dataset.detailTarget || '-';
            fields.room.textContent = button.dataset.detailRoom || '-';
            fields.text.textContent = button.dataset.detailText || '-';
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeModal() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('.detail-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                openModal(button);
            });
        });

        closeButton?.addEventListener('click', closeModal);
        modal?.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal?.classList.contains('open')) {
                closeModal();
            }
        });
    })();
</script>
</body>
</html>

