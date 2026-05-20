<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelas Saya | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/kelas_saya_wali.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="wali-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengguna' }}</div>
                    <h1 class="hero-title">Kelas Saya</h1>
                    <p class="hero-subtitle">
                        Halaman ini menampilkan kelas yang Anda pegang sebagai wali kelas, lengkap dengan ringkasan inventaris dan pengajuan yang masuk dari kelas tersebut.
                    </p>
                </section>

                <section class="room-stack">
                    @forelse ($roomOverviews as $overview)
                        @php
                            $assignment = $overview['assignment'];
                            $inventoryRows = $overview['inventory_rows'];
                            $summary = $overview['summary'];
                            $latestRequests = $overview['latest_requests'];
                            $inboxUrl = \Illuminate\Support\Facades\Route::has('admin.requests.inbox') ? route('admin.requests.inbox') : '#';
                            $historyUrl = route('admin.requests.history');
                        @endphp
                        <article class="room-card">
                            <div class="room-shell">
                                <div class="room-header">
                                    <div>
                                        <div class="room-title">{{ $assignment->nama_ruangan }}</div>
                                        <div class="room-meta">
                                            Kode ruangan: {{ $assignment->kode_ruangan }}<br>
                                            Jenis ruangan: {{ ucfirst($assignment->jenis_ruangan) }}<br>
                                            Wali kelas: {{ $overview['wali_kelas'] }}
                                        </div>
                                    </div>
                                    <div class="room-badge">
                                        <i class="bi bi-person-badge-fill"></i>
                                        {{ str_replace('_', ' ', ucfirst($assignment->peran_ruangan)) }}
                                    </div>
                                </div>

                                <div class="summary-grid">
                                    <div class="summary-card">
                                        <div class="summary-label">Total Barang</div>
                                        <div class="summary-value">{{ number_format($summary['total_barang']) }}</div>
                                        <div class="summary-note">Inventaris tercatat untuk kelas ini</div>
                                    </div>
                                    <div class="summary-card is-accent">
                                        <div class="summary-label">Pengajuan Kelas</div>
                                        <div class="summary-value">{{ number_format($summary['menunggu_review']) }}</div>
                                        <div class="summary-note">Perlu ditinjau oleh wali kelas</div>
                                    </div>
                                    <div class="summary-card">
                                        <div class="summary-label">Total Pengajuan</div>
                                        <div class="summary-value">{{ number_format($summary['total_pengajuan']) }}</div>
                                        <div class="summary-note">Riwayat permintaan dari kelas ini</div>
                                    </div>
                                </div>

                                <div class="room-layout">
                                    <div>
                                        <div class="room-section-title">Daftar Inventaris</div>
                                        @if ($inventoryRows->isEmpty())
                                            <div class="empty-state">Belum ada data barang yang tercatat untuk kelas ini.</div>
                                        @else
                                            <div class="inventory-table-wrap">
                                                <table class="inventory-table mobile-card-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Barang</th>
                                                            <th>Jumlah</th>
                                                            <th>Kondisi</th>
                                                            <th>Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($inventoryRows as $row)
                                                            @php
                                                                $totalJumlah = (int) $row->jumlah_baik + (int) $row->jumlah_rusak;
                                                                $kondisiLabel = (int) $row->jumlah_rusak > 0 ? 'Perlu dicek' : 'Baik';
                                                                $kondisiClass = (int) $row->jumlah_rusak > 0 ? 'bad' : 'good';
                                                            @endphp
                                                            <tr>
                                                                <td><div class="inventory-name">{{ ucfirst($row->nama_barang) }}</div></td>
                                                                <td><span class="inventory-count">{{ $totalJumlah }}</span></td>
                                                                <td><span class="status-badge {{ $kondisiClass }}">{{ $kondisiLabel }}</span></td>
                                                                <td>{{ $row->keterangan ?: '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>

                                    <aside class="insight-stack">
                                        <div class="insight-card">
                                            <div class="room-section-title">Status Kelas</div>
                                            <div class="insight-list">
                                                <div class="insight-item">
                                                    <span class="insight-dot"></span>
                                                    <span>{{ number_format($summary['barang_baik']) }} barang dalam kondisi baik</span>
                                                </div>
                                                <div class="insight-item">
                                                    <span class="insight-dot"></span>
                                                    <span>{{ number_format($summary['barang_rusak']) }} barang perlu perhatian</span>
                                                </div>
                                                <div class="insight-item">
                                                    <span class="insight-dot"></span>
                                                    <span>{{ number_format($summary['pengajuan_aktif']) }} pengajuan masih aktif dari kelas ini</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="insight-card">
                                            <div class="room-section-title">Pengajuan Terbaru</div>
                                            @if ($latestRequests === [])
                                                <div class="empty-state">Belum ada pengajuan terbaru dari kelas ini.</div>
                                            @else
                                                <div class="latest-list">
                                                    @foreach ($latestRequests as $request)
                                                        <div class="latest-item">
                                                            <div class="latest-title">{{ $request['jenis'] }}</div>
                                                            <div class="latest-meta">{{ $request['status'] }} • {{ $request['tanggal'] }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="insight-card">
                                            <div class="room-section-title">Akses Cepat</div>
                                            <div class="quick-links">
                                                <a href="{{ $inboxUrl }}" class="quick-link">
                                                    <span><i class="bi bi-inbox-fill"></i> Lihat Pengajuan Kelas</span>
                                                    <i class="bi bi-arrow-right"></i>
                                                </a>
                                                <a href="{{ $historyUrl }}" class="quick-link">
                                                    <span><i class="bi bi-clock-history"></i> Lihat Riwayat Pengajuan</span>
                                                    <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </aside>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="room-card">
                            <div class="empty-state">Akun wali kelas ini belum memiliki penugasan kelas aktif.</div>
                        </article>
                    @endforelse
                </section>
            </div>
        </main>
    </div>
    @include('chatbot')
</body>
</html>

