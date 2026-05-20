<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Semua Ruangan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/semua_ruangan_kepala.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="owner-rooms-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengguna' }}</div>
                    <h1 class="hero-title">Semua Ruangan</h1>
                    <p class="hero-subtitle">
                        Pantau data ruangan dan inventaris di seluruh sekolah. Halaman ini membantu kepala sekolah melihat kondisi umum setiap ruangan tanpa melakukan perubahan data.
                    </p>
                </section>

                <section class="summary-grid">
                    <article class="summary-card">
                        <div class="summary-label">Total Ruangan</div>
                        <div class="summary-value">{{ number_format($summary['total_ruangan']) }}</div>
                        <div class="summary-note">Semua ruangan yang tercatat di sistem sekolah.</div>
                    </article>
                    <article class="summary-card is-accent">
                        <div class="summary-label">Total Barang</div>
                        <div class="summary-value">{{ number_format($summary['total_barang']) }}</div>
                        <div class="summary-note">Akumulasi inventaris seluruh ruangan sekolah.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Ruangan Aktif</div>
                        <div class="summary-value">{{ number_format($summary['ruangan_aktif']) }}</div>
                        <div class="summary-note">Ruangan yang sudah memiliki inventaris tercatat.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Ruangan Dengan Pengajuan Aktif</div>
                        <div class="summary-value">{{ number_format($summary['ruangan_dengan_pengajuan_aktif']) }}</div>
                        <div class="summary-note">Masih ada pengajuan yang berjalan di ruangan ini.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Barang Bermasalah</div>
                        <div class="summary-value">{{ number_format($summary['ruangan_dengan_barang_bermasalah']) }}</div>
                        <div class="summary-note">Ruangan yang memiliki inventaris perlu perhatian.</div>
                    </article>
                </section>

                <section class="filter-card">
                    <form method="GET" action="{{ route('owner.rooms') }}" class="filter-form">
                        <div class="filter-field">
                            <label for="roomSearch">Cari Ruangan</label>
                            <input
                                type="text"
                                id="roomSearch"
                                name="q"
                                value="{{ $filters['q'] }}"
                                placeholder="Cari nama ruangan atau kode..."
                            >
                        </div>

                        <div class="filter-field">
                            <label for="roomType">Jenis Ruangan</label>
                            <select id="roomType" name="type">
                                <option value="semua" @selected($filters['type'] === 'semua')>Semua jenis ruangan</option>
                                <option value="kelas" @selected($filters['type'] === 'kelas')>Kelas</option>
                                <option value="laboratorium" @selected($filters['type'] === 'laboratorium')>Lab</option>
                                <option value="kantor" @selected($filters['type'] === 'kantor')>Kantor</option>
                            </select>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="filter-btn">
                                <i class="bi bi-search"></i>
                                <span>Terapkan</span>
                            </button>
                            <a href="{{ route('owner.rooms') }}" class="filter-link">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </section>

                @if ($roomCards->isEmpty())
                    <section class="empty-card">
                        Belum ada data ruangan yang sesuai dengan pencarian atau filter yang dipilih.
                    </section>
                @else
                    <section class="room-grid">
                        @foreach ($roomCards as $room)
                            <article class="room-card">
                                <div class="room-top">
                                    <div>
                                        <div class="room-name">{{ $room['nama_ruangan'] }}</div>
                                        <div class="room-code">Kode: {{ $room['kode_ruangan'] }}</div>
                                        <div class="room-type">Jenis: {{ $room['jenis_ruangan'] }}</div>
                                    </div>
                                    <span class="room-badge {{ $room['status_class'] }}">
                                        {{ $room['status_label'] }}
                                    </span>
                                </div>

                                <div class="room-metrics">
                                    <div class="metric-box">
                                        <div class="metric-label">Total Barang</div>
                                        <div class="metric-value">{{ number_format($room['total_barang']) }}</div>
                                        <div class="metric-note">{{ number_format($room['barang_baik']) }} baik, {{ number_format($room['barang_rusak']) }} perlu perhatian</div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="metric-label">Pengajuan Aktif</div>
                                        <div class="metric-value">{{ number_format($room['pengajuan_aktif']) }}</div>
                                        <div class="metric-note">Permintaan yang masih berjalan di ruangan ini</div>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    class="room-link-btn room-detail-trigger"
                                    data-room='@json($room)'
                                >
                                    <span>Lihat Detail</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </article>
                        @endforeach
                    </section>

                    @if ($rooms->hasPages())
                        <div class="pagination-wrap">
                            <div class="pagination">
                                <span class="pagination-info">Halaman {{ $rooms->currentPage() }} dari {{ $rooms->lastPage() }}</span>

                                @if ($rooms->onFirstPage())
                                    <span class="pagination-link disabled">
                                        <i class="bi bi-chevron-left"></i>
                                    </span>
                                @else
                                    <a href="{{ $rooms->previousPageUrl() }}" class="pagination-link" aria-label="Halaman sebelumnya">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                @endif

                                @foreach ($rooms->getUrlRange(1, $rooms->lastPage()) as $page => $url)
                                    @if ($page === $rooms->currentPage())
                                        <span class="pagination-current">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if ($rooms->hasMorePages())
                                    <a href="{{ $rooms->nextPageUrl() }}" class="pagination-link" aria-label="Halaman berikutnya">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="pagination-link disabled">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </main>
    </div>

    <div class="room-modal" id="roomDetailModal" aria-hidden="true">
        <div class="room-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="roomModalTitle">
            <div class="room-modal-header">
                <div>
                    <div class="room-modal-title" id="roomModalTitle">Detail Ruangan</div>
                    <div class="room-modal-meta" id="roomModalMeta"></div>
                </div>
                <button type="button" class="room-modal-close" id="roomModalClose" aria-label="Tutup detail ruangan">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="room-modal-body">
                <div class="room-modal-grid">
                    <div class="room-modal-card">
                        <div class="room-modal-label">Total Barang</div>
                        <div class="room-modal-value" id="roomModalTotalBarang">0</div>
                    </div>
                    <div class="room-modal-card">
                        <div class="room-modal-label">Pengajuan Aktif</div>
                        <div class="room-modal-value" id="roomModalPengajuan">0</div>
                    </div>
                    <div class="room-modal-card">
                        <div class="room-modal-label">Status</div>
                        <div class="room-modal-value" id="roomModalStatus">Normal</div>
                    </div>
                </div>

                <div class="room-modal-section">
                    <div class="detail-section-title">Kondisi Umum</div>
                    <div class="detail-list" id="roomModalConditionList"></div>
                </div>

                <div class="room-modal-section">
                    <div class="detail-section-title">Inventaris Singkat</div>
                    <div id="roomModalInventory"></div>
                </div>

                <div class="room-modal-section">
                    <div class="detail-section-title">Pengajuan Terkini</div>
                    <div id="roomModalLatestRequest"></div>
                </div>
            </div>
        </div>
    </div>

    @include('chatbot')
    <script>
        (function () {
            const modal = document.getElementById('roomDetailModal');
            const closeButton = document.getElementById('roomModalClose');
            const title = document.getElementById('roomModalTitle');
            const meta = document.getElementById('roomModalMeta');
            const totalBarang = document.getElementById('roomModalTotalBarang');
            const pengajuan = document.getElementById('roomModalPengajuan');
            const status = document.getElementById('roomModalStatus');
            const conditionList = document.getElementById('roomModalConditionList');
            const inventory = document.getElementById('roomModalInventory');
            const latestRequest = document.getElementById('roomModalLatestRequest');

            if (!modal) {
                return;
            }

            function renderList(target, items, emptyText) {
                target.innerHTML = '';

                if (!items.length) {
                    const empty = document.createElement('div');
                    empty.className = 'detail-empty';
                    empty.textContent = emptyText;
                    target.appendChild(empty);
                    return;
                }

                const list = document.createElement('div');
                list.className = 'detail-list';

                items.forEach(function (text) {
                    const item = document.createElement('div');
                    item.className = 'detail-item';
                    const dot = document.createElement('span');
                    dot.className = 'detail-dot';
                    const value = document.createElement('span');
                    value.className = 'notranslate';
                    value.setAttribute('translate', 'no');
                    value.textContent = text;
                    item.append(dot, value);
                    list.appendChild(item);
                });

                target.appendChild(list);
            }

            function openModal(room) {
                title.classList.add('notranslate');
                title.setAttribute('translate', 'no');
                meta.classList.add('notranslate');
                meta.setAttribute('translate', 'no');
                title.textContent = room.nama_ruangan || 'Detail Ruangan';
                meta.textContent = 'Kode: ' + (room.kode_ruangan || '-') + ' | Jenis: ' + (room.jenis_ruangan || '-');
                totalBarang.textContent = String(room.total_barang ?? 0);
                pengajuan.textContent = String(room.pengajuan_aktif ?? 0);
                status.textContent = room.status_label || 'Normal';

                renderList(conditionList, [
                    (room.barang_baik ?? 0) + ' barang dalam kondisi baik',
                    (room.barang_rusak ?? 0) + ' barang perlu perhatian',
                    (room.pengajuan_aktif ?? 0) + ' pengajuan aktif pada ruangan ini'
                ], 'Belum ada ringkasan kondisi.');

                const inventoryItems = Array.isArray(room.detail_items)
                    ? room.detail_items.map(function (item) {
                        return item.nama_barang + ' | ' + item.jumlah + ' | ' + item.kondisi;
                    })
                    : [];

                renderList(inventory, inventoryItems, 'Belum ada inventaris yang tercatat untuk ruangan ini.');

                const latestItems = room.latest_request
                    ? [room.latest_request.barang + ' | ' + room.latest_request.status + ' | ' + room.latest_request.tanggal]
                    : [];

                renderList(latestRequest, latestItems, 'Belum ada pengajuan terbaru untuk ruangan ini.');

                modal.classList.add('open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('.room-detail-trigger').forEach(function (button) {
                button.addEventListener('click', function () {
                    const room = JSON.parse(button.dataset.room || '{}');
                    openModal(room);
                });
            });

            closeButton?.addEventListener('click', closeModal);

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && modal.classList.contains('open')) {
                    closeModal();
                }
            });
        })();
    </script>
</body>
</html>

