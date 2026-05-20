<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/laporan_kepala.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="owner-reports-page">
            <div class="page-shell">
            <section class="hero-card">
                <div class="eyebrow">Kepala Sekolah</div>
                <h1 class="hero-title">Laporan</h1>
                <p class="hero-subtitle">Lihat ringkasan data inventaris dan pengajuan di seluruh sekolah untuk membantu monitoring dan pengambilan keputusan.</p>
            </section>

            <section class="tab-card">
                <div class="tab-row">
                    <a href="{{ route('owner.reports', ['section' => 'inventory', 'month' => $month, 'year' => $year]) }}" @class(['tab-pill', 'active' => $section === 'inventory'])>Laporan Inventaris</a>
                    <a href="{{ route('owner.reports', ['section' => 'requests', 'month' => $month, 'year' => $year]) }}" @class(['tab-pill', 'active' => $section === 'requests'])>Laporan Pengajuan</a>
                    <a href="{{ route('owner.reports', ['section' => 'classes', 'month' => $month, 'year' => $year]) }}" @class(['tab-pill', 'active' => $section === 'classes'])>Laporan Per Kelas</a>
                </div>
            </section>

            <section class="filter-card">
                <form method="GET" class="filter-form">
                    <input type="hidden" name="section" value="{{ $section }}">
                    <div class="filter-controls">
                        <div class="filter-field">
                            <label for="month">Bulan</label>
                            <select id="month" name="month">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" @selected($month === $i)>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="filter-field">
                            <label for="year">Tahun</label>
                            <select id="year" name="year">
                                @foreach ($yearOptions as $option)
                                    <option value="{{ $option }}" @selected($year === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn">Terapkan</button>
                        <button type="button" class="action-btn" onclick="window.print()">
                            <i class="bi bi-printer"></i>
                            Print
                        </button>
                        <a href="{{ route('owner.reports.export', ['section' => $section, 'month' => $month, 'year' => $year, 'format' => 'excel']) }}" class="action-btn">
                            <i class="bi bi-file-earmark-excel"></i>
                            Export Excel
                        </a>
                        <a href="{{ route('owner.reports.export', ['section' => $section, 'month' => $month, 'year' => $year, 'format' => 'word']) }}" class="action-btn">
                            <i class="bi bi-file-earmark-word"></i>
                            Export Word
                        </a>
                    </div>
                </form>
            </section>

            <div class="report-print-area">
            @if ($section === 'inventory')
                <section class="summary-grid">
                    <article class="summary-card">
                        <div class="summary-label">Total Jenis Barang</div>
                        <div class="summary-value">{{ number_format($inventorySummary['total_jenis']) }}</div>
                        <div class="summary-note">Jenis barang yang tercatat di seluruh sekolah</div>
                    </article>
                    <article class="summary-card is-accent">
                        <div class="summary-label">Total Barang</div>
                        <div class="summary-value">{{ number_format($inventorySummary['total_barang']) }}</div>
                        <div class="summary-note">Jumlah barang dari seluruh ruangan</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Barang Baik</div>
                        <div class="summary-value">{{ number_format($inventorySummary['barang_baik']) }}</div>
                        <div class="summary-note">Barang dalam kondisi baik</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Perlu Perhatian</div>
                        <div class="summary-value">{{ number_format($inventorySummary['barang_rusak']) }}</div>
                        <div class="summary-note">Barang yang perlu diperhatikan</div>
                    </article>
                </section>

                @if ($inventoryRows->isEmpty())
                    <section class="empty-card">Belum ada data inventaris sekolah.</section>
                @else
                    <section class="table-card">
                        <div class="table-wrap">
                            <table class="report-table mobile-card-table">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Total</th>
                                        <th>Baik</th>
                                        <th>Rusak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventoryRows as $row)
                                        <tr>
                                            <td data-label="Barang"><div class="cell-primary">{{ $row['nama_barang'] }}</div></td>
                                            <td data-label="Total">{{ number_format($row['total']) }}</td>
                                            <td data-label="Baik">{{ number_format($row['baik']) }}</td>
                                            <td data-label="Rusak">{{ number_format($row['rusak']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            @elseif ($section === 'requests')
                <section class="summary-grid">
                    <article class="summary-card">
                        <div class="summary-label">Total Pengajuan</div>
                        <div class="summary-value">{{ number_format($requestSummary['total']) }}</div>
                        <div class="summary-note">Pengajuan pada periode yang dipilih</div>
                    </article>
                    <article class="summary-card is-accent">
                        <div class="summary-label">Diproses</div>
                        <div class="summary-value">{{ number_format($requestSummary['process']) }}</div>
                        <div class="summary-note">Pengajuan yang masih dalam proses</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Disetujui</div>
                        <div class="summary-value">{{ number_format($requestSummary['approved']) }}</div>
                        <div class="summary-note">Pengajuan yang disetujui</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Ditolak</div>
                        <div class="summary-value">{{ number_format($requestSummary['rejected']) }}</div>
                        <div class="summary-note">Pengajuan yang ditolak</div>
                    </article>
                </section>

                @if ($requestRows->isEmpty())
                    <section class="empty-card">Belum ada data pengajuan pada periode yang dipilih.</section>
                @else
                    <section class="table-card">
                        <div class="table-wrap">
                            <table class="report-table mobile-card-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th>Kelas</th>
                                        <th>Peminta</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requestRows as $row)
                                        <tr>
                                            <td data-label="Tanggal">{{ $row['tanggal'] }}</td>
                                            <td data-label="Barang"><div class="cell-primary">{{ $row['barang'] }}</div></td>
                                            <td data-label="Kelas">{{ $row['kelas'] }}</td>
                                            <td data-label="Peminta">{{ $row['peminta'] }}</td>
                                            <td data-label="Jenis">{{ $row['jenis'] }}</td>
                                            <td data-label="Jumlah">{{ number_format($row['jumlah']) }}</td>
                                            <td data-label="Status"><span class="badge {{ $row['status_class'] }}">{{ $row['status'] }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            @else
                <section class="summary-grid">
                    <article class="summary-card">
                        <div class="summary-label">Total Kelas</div>
                        <div class="summary-value">{{ number_format($classSummary['total_kelas']) }}</div>
                        <div class="summary-note">Jumlah kelas yang tercatat dalam laporan</div>
                    </article>
                    <article class="summary-card is-accent">
                        <div class="summary-label">Total Barang</div>
                        <div class="summary-value">{{ number_format($classSummary['total_barang']) }}</div>
                        <div class="summary-note">Total inventaris pada seluruh kelas</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Barang Rusak</div>
                        <div class="summary-value">{{ number_format($classSummary['barang_rusak']) }}</div>
                        <div class="summary-note">Barang yang perlu perhatian</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Total Pengajuan</div>
                        <div class="summary-value">{{ number_format($classSummary['total_pengajuan']) }}</div>
                        <div class="summary-note">Pengajuan seluruh kelas pada periode ini</div>
                    </article>
                </section>

                @if ($classRows->isEmpty())
                    <section class="empty-card">Belum ada data kelas yang dapat ditampilkan.</section>
                @else
                    <section class="table-card">
                        <div class="table-wrap">
                            <table class="report-table mobile-card-table">
                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th>Total Barang</th>
                                        <th>Baik</th>
                                        <th>Rusak</th>
                                        <th>Pengajuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classRows as $row)
                                        <tr>
                                            <td data-label="Kelas">
                                                <div class="cell-primary">{{ $row['kelas'] }}</div>
                                                <div class="cell-secondary">{{ $row['kode'] }}</div>
                                            </td>
                                            <td data-label="Total Barang">{{ number_format($row['total_barang']) }}</td>
                                            <td data-label="Baik">{{ number_format($row['baik']) }}</td>
                                            <td data-label="Rusak">{{ number_format($row['rusak']) }}</td>
                                            <td data-label="Pengajuan">{{ number_format($row['pengajuan']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            @endif
            </div>
            </div>
        </main>

        @include('chatbot')
    </div>
</body>
</html>

