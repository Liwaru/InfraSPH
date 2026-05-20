<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventaris Sekolah | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/inventaris_sekolah_kepala.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="owner-inventory-page">
            <div class="page-shell">
            <section class="hero-card">
                <div class="eyebrow">Kepala Sekolah</div>
                <h1 class="hero-title">Inventaris Sekolah</h1>
                <p class="hero-subtitle">Lihat dan pantau seluruh data barang di sekolah dalam bentuk rekap gabungan dari semua ruangan, lengkap dengan kondisi dan distribusinya.</p>
            </section>

            <section class="summary-grid">
                <article class="summary-card">
                    <div class="summary-label">Total Jenis Barang</div>
                    <div class="summary-value">{{ number_format($summary['total_jenis_barang']) }}</div>
                    <div class="summary-note">Jenis barang aktif yang tercatat di sekolah</div>
                </article>
                <article class="summary-card is-accent">
                    <div class="summary-label">Total Barang</div>
                    <div class="summary-value">{{ number_format($summary['total_barang']) }}</div>
                    <div class="summary-note">Jumlah barang dari seluruh ruangan</div>
                </article>
                <article class="summary-card">
                    <div class="summary-label">Barang Baik</div>
                    <div class="summary-value">{{ number_format($summary['barang_baik']) }}</div>
                    <div class="summary-note">Barang dalam kondisi baik dan siap digunakan</div>
                </article>
                <article class="summary-card">
                    <div class="summary-label">Perlu Perhatian</div>
                    <div class="summary-value">{{ number_format($summary['perlu_perhatian']) }}</div>
                    <div class="summary-note">Barang yang perlu dicek atau ditindaklanjuti</div>
                </article>
            </section>

            <section class="filter-card">
                <form method="GET" class="filter-form">
                    <div class="filter-field">
                        <label for="inventory-search">Cari Barang</label>
                        <input id="inventory-search" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama barang...">
                    </div>
                    <div class="filter-field">
                        <label for="inventory-status">Filter Kondisi</label>
                        <select id="inventory-status" name="status">
                            <option value="semua" @selected($filters['status'] === 'semua')>Semua</option>
                            <option value="baik" @selected($filters['status'] === 'baik')>Baik</option>
                            <option value="perlu_perhatian" @selected($filters['status'] === 'perlu_perhatian')>Perlu Perhatian</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn">Terapkan</button>
                        <a href="{{ route('owner.inventories') }}" class="filter-link">Reset</a>
                    </div>
                </form>
            </section>

            @if ($inventoryRows->isEmpty())
                <section class="empty-card">Belum ada data inventaris yang sesuai dengan filter saat ini.</section>
            @else
                <section class="table-card">
                    <div class="table-wrap">
                        <table class="inventory-table mobile-card-table">
                            <thead>
                                <tr>
                                    <th class="col-name">Nama Barang</th>
                                    <th class="col-small">Total</th>
                                    <th class="col-small">Baik</th>
                                    <th class="col-small">Rusak</th>
                                    <th class="col-small">Satuan</th>
                                    <th class="col-small">Status</th>
                                    <th class="col-small">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventoryRows as $row)
                                    <tr>
                                        <td data-label="Nama Barang">
                                            <div class="inventory-name">{{ $row['nama_barang'] }}</div>
                                            <div class="inventory-unit">Rekap inventaris seluruh sekolah</div>
                                        </td>
                                        <td data-label="Total"><span class="inventory-number">{{ number_format($row['total_barang']) }}</span></td>
                                        <td data-label="Baik"><span class="inventory-number">{{ number_format($row['total_baik']) }}</span></td>
                                        <td data-label="Rusak"><span class="inventory-number">{{ number_format($row['total_rusak']) }}</span></td>
                                        <td data-label="Satuan">{{ $row['satuan'] }}</td>
                                        <td data-label="Status"><span class="status-badge {{ $row['status_class'] }}">{{ $row['status_label'] }}</span></td>
                                        <td data-label="Aksi">
                                            <button
                                                type="button"
                                                class="detail-btn js-inventory-detail"
                                                data-inventory='@json($row)'
                                            >
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                @if ($inventories->hasPages())
                    <div class="pagination-wrap">
                        <nav class="pagination" aria-label="Pagination inventaris sekolah">
                            <span class="pagination-info">
                                Showing {{ $inventories->firstItem() }} to {{ $inventories->lastItem() }} of {{ $inventories->total() }} results
                            </span>

                            @if ($inventories->onFirstPage())
                                <span class="pagination-link disabled"><i class="bi bi-chevron-left"></i></span>
                            @else
                                <a class="pagination-link" href="{{ $inventories->previousPageUrl() }}" aria-label="Halaman sebelumnya">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            @endif

                            @foreach ($inventories->getUrlRange(1, $inventories->lastPage()) as $page => $url)
                                @if ($page === $inventories->currentPage())
                                    <span class="pagination-current">{{ $page }}</span>
                                @else
                                    <a class="pagination-link" href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($inventories->hasMorePages())
                                <a class="pagination-link" href="{{ $inventories->nextPageUrl() }}" aria-label="Halaman berikutnya">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            @else
                                <span class="pagination-link disabled"><i class="bi bi-chevron-right"></i></span>
                            @endif
                        </nav>
                    </div>
                @endif
            @endif
            </div>
        </main>

        <div class="inventory-modal" id="inventory-modal" aria-hidden="true">
            <div class="inventory-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="inventory-modal-title">
                <div class="inventory-modal-header">
                    <div>
                        <div class="inventory-modal-title" id="inventory-modal-title">Detail Inventaris</div>
                        <div class="inventory-modal-meta" id="inventory-modal-meta">Distribusi inventaris seluruh sekolah</div>
                    </div>
                    <button type="button" class="inventory-modal-close" id="inventory-modal-close" aria-label="Tutup detail inventaris">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="inventory-modal-body">
                    <div class="inventory-modal-grid">
                        <div class="inventory-modal-card">
                            <div class="inventory-modal-label">Total</div>
                            <div class="inventory-modal-value" id="inventory-modal-total">0</div>
                        </div>
                        <div class="inventory-modal-card">
                            <div class="inventory-modal-label">Baik</div>
                            <div class="inventory-modal-value" id="inventory-modal-good">0</div>
                        </div>
                        <div class="inventory-modal-card">
                            <div class="inventory-modal-label">Rusak</div>
                            <div class="inventory-modal-value" id="inventory-modal-bad">0</div>
                        </div>
                        <div class="inventory-modal-card">
                            <div class="inventory-modal-label">Satuan</div>
                            <div class="inventory-modal-value" id="inventory-modal-unit">-</div>
                        </div>
                    </div>

                    <section class="inventory-modal-section">
                        <div class="inventory-modal-label distribution-title">Distribusi Per Ruangan</div>
                        <div class="distribution-list" id="inventory-modal-distribution"></div>
                    </section>
                </div>
            </div>
        </div>

        @include('chatbot')
    </div>

    <script>
        (() => {
            const modal = document.getElementById('inventory-modal');
            const closeBtn = document.getElementById('inventory-modal-close');
            const titleEl = document.getElementById('inventory-modal-title');
            const metaEl = document.getElementById('inventory-modal-meta');
            const totalEl = document.getElementById('inventory-modal-total');
            const goodEl = document.getElementById('inventory-modal-good');
            const badEl = document.getElementById('inventory-modal-bad');
            const unitEl = document.getElementById('inventory-modal-unit');
            const distributionEl = document.getElementById('inventory-modal-distribution');

            const openModal = (inventory) => {
                titleEl.classList.add('notranslate');
                titleEl.setAttribute('translate', 'no');
                unitEl.classList.add('notranslate');
                unitEl.setAttribute('translate', 'no');
                titleEl.textContent = inventory.nama_barang;
                metaEl.textContent = `Rekap distribusi ${inventory.nama_barang.toLowerCase()} di seluruh sekolah`;
                totalEl.textContent = inventory.total_barang;
                goodEl.textContent = inventory.total_baik;
                badEl.textContent = inventory.total_rusak;
                unitEl.textContent = inventory.satuan || '-';

                distributionEl.innerHTML = '';

                if (!Array.isArray(inventory.distribution) || inventory.distribution.length === 0) {
                    const empty = document.createElement('div');
                    empty.className = 'distribution-empty';
                    empty.textContent = 'Belum ada distribusi ruangan yang tercatat untuk barang ini.';
                    distributionEl.appendChild(empty);
                } else {
                    inventory.distribution.forEach((item) => {
                        const row = document.createElement('div');
                        row.className = 'distribution-item';

                        const roomWrap = document.createElement('div');
                        const roomName = document.createElement('div');
                        roomName.className = 'distribution-room notranslate';
                        roomName.setAttribute('translate', 'no');
                        roomName.textContent = item.ruangan || '-';
                        const roomCode = document.createElement('div');
                        roomCode.className = 'distribution-code notranslate';
                        roomCode.setAttribute('translate', 'no');
                        roomCode.textContent = item.kode || '-';
                        roomWrap.append(roomName, roomCode);

                        const stats = document.createElement('div');
                        stats.className = 'distribution-stats';
                        ['Total: ' + (item.total ?? 0), 'Baik: ' + (item.baik ?? 0), 'Rusak: ' + (item.rusak ?? 0)].forEach((text) => {
                            const stat = document.createElement('span');
                            stat.textContent = text;
                            stats.appendChild(stat);
                        });

                        row.append(roomWrap, stats);
                        distributionEl.appendChild(row);
                    });
                }

                modal.classList.add('open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            const closeModal = () => {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            document.querySelectorAll('.js-inventory-detail').forEach((button) => {
                button.addEventListener('click', () => {
                    const raw = button.getAttribute('data-inventory');

                    if (!raw) {
                        return;
                    }

                    try {
                        openModal(JSON.parse(raw));
                    } catch (error) {
                        console.error('Gagal membaca detail inventaris.', error);
                    }
                });
            });

            closeBtn?.addEventListener('click', closeModal);
            modal?.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal?.classList.contains('open')) {
                    closeModal();
                }
            });
        })();
    </script>
</body>
</html>

