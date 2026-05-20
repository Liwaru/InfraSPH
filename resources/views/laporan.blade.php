<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/laporan.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="superadmin-reports-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengelola Sistem' }}</div>
                    <h1 class="hero-title">Laporan</h1>
                    <p class="hero-subtitle">Pantau inventaris sekolah, barang masuk, kondisi barang, dan realisasi pengajuan dari satu halaman rekap yang siap difilter dan diexport.</p>
                </section>

                <section class="filter-card">
                    <form method="GET" action="{{ route('superadmin.reports') }}" class="filter-form">
                        <input type="hidden" name="section" value="{{ $section }}">
                        <div class="filter-left">
                            <div class="filter-field">
                                <label for="dateFrom">Periode Dari</label>
                                <input id="dateFrom" type="date" name="date_from" value="{{ $filters['date_from'] }}">
                            </div>
                            <div class="filter-field">
                                <label for="dateTo">Periode Sampai</label>
                                <input id="dateTo" type="date" name="date_to" value="{{ $filters['date_to'] }}">
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
                                <label for="typeFilter">Jenis Ruangan</label>
                                <select id="typeFilter" name="room_type">
                                    <option value="semua" @selected($filters['room_type'] === 'semua')>Semua Jenis</option>
                                    @foreach ($roomTypeOptions as $option)
                                        <option value="{{ $option }}" @selected($filters['room_type'] === $option)>{{ $option === 'kantor_guru' ? 'Kantor Guru' : ucfirst($option) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-field">
                                <label for="categoryFilter">Kategori Barang</label>
                                <select id="categoryFilter" name="category">
                                    <option value="semua" @selected($filters['category'] === 'semua')>Semua Kategori</option>
                                    @foreach ($categoryOptions as $option)
                                        <option value="{{ $option->id_kategori_barang }}" @selected($filters['category'] === (string) $option->id_kategori_barang)>{{ ucfirst($option->nama_kategori) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-field">
                                <label for="conditionFilter">Kondisi Barang</label>
                                <select id="conditionFilter" name="condition">
                                    <option value="semua" @selected($filters['condition'] === 'semua')>Semua Kondisi</option>
                                    <option value="baik" @selected($filters['condition'] === 'baik')>Baik</option>
                                    <option value="rusak" @selected($filters['condition'] === 'rusak')>Rusak</option>
                                    <option value="perlu_perbaikan" @selected($filters['condition'] === 'perlu_perbaikan')>Perlu Perbaikan</option>
                                </select>
                            </div>
                            <div class="filter-field">
                                <label for="requestStatusFilter">Status Pengajuan / Realisasi</label>
                                <select id="requestStatusFilter" name="request_status">
                                    <option value="semua" @selected($filters['request_status'] === 'semua')>Semua Status</option>
                                    <option value="direalisasi" @selected($filters['request_status'] === 'direalisasi')>Direalisasi</option>
                                    <option value="menunggu" @selected($filters['request_status'] === 'menunggu')>Menunggu</option>
                                    <option value="ditolak" @selected($filters['request_status'] === 'ditolak')>Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="filter-btn">
                                <i class="bi bi-funnel-fill"></i>
                                <span>Terapkan</span>
                            </button>
                            <a href="{{ route('superadmin.reports', ['section' => $section]) }}" class="action-btn">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span>Reset</span>
                            </a>
                            <a href="{{ route('superadmin.reports.export', array_merge(['section' => $section, 'format' => 'excel'], $filters)) }}" class="action-btn">
                                <i class="bi bi-file-earmark-excel"></i>
                                <span>Export Excel</span>
                            </a>
                            <a href="{{ route('superadmin.reports.export', array_merge(['section' => $section, 'format' => 'word'], $filters)) }}" class="action-btn">
                                <i class="bi bi-file-earmark-word"></i>
                                <span>Export Word</span>
                            </a>
                            <a href="{{ route('superadmin.reports.export', array_merge(['section' => $section, 'format' => 'print'], $filters)) }}" class="action-btn" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-printer"></i>
                                <span>Cetak / PDF</span>
                            </a>
                        </div>
                    </form>
                </section>

                <section class="summary-grid">
                    <article class="summary-card">
                        <div class="summary-label">Total Inventaris</div>
                        <div class="summary-value">{{ number_format($summary['total_inventaris']) }}</div>
                        <div class="summary-note">Total unit barang dari seluruh inventaris sekolah.</div>
                    </article>
                    <article class="summary-card is-accent">
                        <div class="summary-label">Total Barang Masuk</div>
                        <div class="summary-value">{{ number_format($summary['total_barang_masuk']) }}</div>
                        <div class="summary-note">Akumulasi barang masuk dari data realisasi yang tercatat.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Total Barang Rusak</div>
                        <div class="summary-value">{{ number_format($summary['total_barang_rusak']) }}</div>
                        <div class="summary-note">Unit barang rusak di seluruh ruangan.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Pengajuan Direalisasi</div>
                        <div class="summary-value">{{ number_format($summary['total_pengajuan_direalisasi']) }}</div>
                        <div class="summary-note">Jumlah pengajuan yang sudah masuk inventaris.</div>
                    </article>
                </section>

                <section class="tab-card">
                    <div class="tab-row">
                        <a href="{{ route('superadmin.reports', array_merge($filters, ['section' => 'inventory'])) }}" @class(['tab-pill', 'active' => $section === 'inventory'])>
                            <i class="bi bi-box-seam"></i>
                            <span>Inventaris</span>
                        </a>
                        <a href="{{ route('superadmin.reports', array_merge($filters, ['section' => 'incoming'])) }}" @class(['tab-pill', 'active' => $section === 'incoming'])>
                            <i class="bi bi-box-arrow-in-down"></i>
                            <span>Barang Masuk</span>
                        </a>
                        <a href="{{ route('superadmin.reports', array_merge($filters, ['section' => 'condition'])) }}" @class(['tab-pill', 'active' => $section === 'condition'])>
                            <i class="bi bi-clipboard2-pulse"></i>
                            <span>Kondisi Barang</span>
                        </a>
                        <a href="{{ route('superadmin.reports', array_merge($filters, ['section' => 'requests'])) }}" @class(['tab-pill', 'active' => $section === 'requests'])>
                            <i class="bi bi-journal-check"></i>
                            <span>Pengajuan</span>
                        </a>
                    </div>
                </section>

                <section class="table-card">
                    <div class="table-header">
                        <div>
                            <div class="table-title">
                                @if ($section === 'incoming')
                                    Laporan Barang Masuk ke Ruangan
                                @elseif ($section === 'condition')
                                    Laporan Kondisi Barang
                                @elseif ($section === 'requests')
                                    Laporan Pengajuan Direalisasi
                                @else
                                    Laporan Inventaris per Ruangan
                                @endif
                            </div>
                            <div class="table-subtitle">Menampilkan {{ number_format($rows->total()) }} data berdasarkan filter aktif.</div>
                        </div>
                    </div>

                    @if ($rows->count() === 0)
                        <div class="empty-card">Belum ada data laporan yang cocok dengan filter saat ini.</div>
                    @else
                        <div class="table-wrap">
                            <table class="mobile-card-table">
                                <thead>
                                    @if ($section === 'incoming')
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Ruangan</th>
                                            <th>Jenis Ruangan</th>
                                            <th>Jumlah</th>
                                            <th>Sumber</th>
                                            <th>Ditambahkan Oleh</th>
                                        </tr>
                                    @elseif ($section === 'condition')
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Ruangan</th>
                                            <th>Jumlah Baik</th>
                                            <th>Jumlah Rusak</th>
                                            <th>Kondisi</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    @elseif ($section === 'requests')
                                        <tr>
                                            <th>Pengaju</th>
                                            <th>Barang</th>
                                            <th>Ruangan</th>
                                            <th>Jumlah</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Tanggal Realisasi</th>
                                            <th>Status Admin</th>
                                            <th>Status Owner</th>
                                            <th>Status Realisasi</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Ruangan</th>
                                            <th>Barang</th>
                                            <th>Kategori</th>
                                            <th>Jumlah</th>
                                            <th>Kondisi</th>
                                            <th>Tanggal Masuk</th>
                                        </tr>
                                    @endif
                                </thead>
                                <tbody>
                                    @foreach ($rows as $row)
                                        @if ($section === 'incoming')
                                            <tr>
                                                <td data-label="Tanggal">{{ $row['tanggal'] }}</td>
                                                <td data-label="Nama Barang">{{ $row['barang'] }}</td>
                                                <td data-label="Ruangan">{{ $row['ruangan'] }}</td>
                                                <td data-label="Jenis Ruangan">{{ $row['jenis_ruangan'] }}</td>
                                                <td data-label="Jumlah">{{ number_format($row['jumlah']) }}</td>
                                                <td data-label="Sumber">{{ $row['sumber'] }}</td>
                                                <td data-label="Ditambahkan Oleh">{{ $row['ditambahkan_oleh'] }}</td>
                                            </tr>
                                        @elseif ($section === 'condition')
                                            <tr>
                                                <td data-label="Nama Barang">{{ $row['barang'] }}</td>
                                                <td data-label="Ruangan">{{ $row['ruangan'] }}</td>
                                                <td data-label="Jumlah Baik">{{ number_format($row['jumlah_baik']) }}</td>
                                                <td data-label="Jumlah Rusak">{{ number_format($row['jumlah_rusak']) }}</td>
                                                <td data-label="Kondisi"><span class="pill {{ $row['kondisi_class'] }}">{{ $row['kondisi'] }}</span></td>
                                                <td data-label="Keterangan">{{ $row['keterangan'] }}</td>
                                            </tr>
                                        @elseif ($section === 'requests')
                                            <tr>
                                                <td data-label="Pengaju">{{ $row['pengaju'] }}</td>
                                                <td data-label="Barang">{{ $row['barang'] }}</td>
                                                <td data-label="Ruangan">{{ $row['ruangan'] }}</td>
                                                <td data-label="Jumlah">{{ number_format($row['jumlah']) }}</td>
                                                <td data-label="Tanggal Pengajuan">{{ $row['tanggal_pengajuan'] }}</td>
                                                <td data-label="Tanggal Realisasi">{{ $row['tanggal_realisasi'] }}</td>
                                                <td data-label="Status Admin">{{ $row['status_admin'] }}</td>
                                                <td data-label="Status Owner">{{ $row['status_owner'] }}</td>
                                                <td data-label="Status Realisasi"><span class="pill {{ $row['status_realisasi_class'] }}">{{ $row['status_realisasi'] }}</span></td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td data-label="Ruangan">{{ $row['ruangan'] }}</td>
                                                <td data-label="Barang">{{ $row['barang'] }}</td>
                                                <td data-label="Kategori">{{ $row['kategori'] }}</td>
                                                <td data-label="Jumlah">{{ number_format($row['jumlah']) }}</td>
                                                <td data-label="Kondisi"><span class="pill {{ $row['kondisi_class'] }}">{{ $row['kondisi'] }}</span></td>
                                                <td data-label="Tanggal Masuk">{{ $row['tanggal_masuk'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($rows->hasPages())
                            <div class="pagination-wrap">
                                <div class="pagination">
                                    <span class="pagination-info">Halaman {{ $rows->currentPage() }} dari {{ $rows->lastPage() }}</span>

                                    @if ($rows->onFirstPage())
                                        <span class="pagination-link disabled"><i class="bi bi-chevron-left"></i></span>
                                    @else
                                        <a href="{{ $rows->previousPageUrl() }}" class="pagination-link" aria-label="Halaman sebelumnya">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    @endif

                                    @foreach ($rows->getUrlRange(1, $rows->lastPage()) as $page => $url)
                                        @if ($page === $rows->currentPage())
                                            <span class="pagination-current">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if ($rows->hasMorePages())
                                        <a href="{{ $rows->nextPageUrl() }}" class="pagination-link" aria-label="Halaman berikutnya">
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
</body>
</html>

