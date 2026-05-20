

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelas Saya | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/kelas_saya.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="kelas-page">
            <div class="page-shell">
            <section class="hero-card">
                <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengguna' }}</div>
                <h1 class="hero-title">Kelas Saya</h1>
                <p class="hero-subtitle">
                    Halaman ini menampilkan gambaran lengkap kelas atau ruangan yang terhubung ke akunmu, mulai dari info ruangan, ringkasan inventaris, aksi cepat, hingga daftar barang yang tercatat.
                </p>
            </section>

            <section class="room-stack">
                @forelse ($roomOverviews as $overview)
                    @php
                        $assignment = $overview['assignment'];
                        $inventoryRows = $overview['inventory_rows'];
                        $summary = $overview['summary'];
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
                                    <i class="bi bi-door-open-fill"></i>
                                    {{ str_replace('_', ' ', ucfirst($assignment->peran_ruangan)) }}
                                </div>
                            </div>

                            <div class="summary-grid">
                                <div class="summary-card">
                                    <div class="summary-label">Total Barang</div>
                                    <div class="summary-value">{{ number_format($summary['total_barang']) }}</div>
                                    <div class="summary-note">{{ number_format($summary['barang_baik']) }} aman dan {{ number_format($summary['barang_rusak']) }} perlu dicek</div>
                                </div>
                                <div class="summary-card is-accent">
                                    <div class="summary-label">Pengajuan</div>
                                    <div class="summary-value">{{ number_format($summary['pengajuan_aktif']) }}</div>
                                    <div class="summary-note">Pengajuan aktif dari ruangan ini</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-label">Penanggung Jawab</div>
                                    <div class="summary-value summary-value-sm">{{ $overview['wali_kelas'] }}</div>
                                    <div class="summary-note">Kontak utama untuk tindak lanjut kelas</div>
                                </div>
                            </div>

                            <div class="room-layout">
                                <div class="room-main">
                                    <div class="room-section-title">Daftar Inventaris</div>
                                    @if ($inventoryRows->isEmpty())
                                        <div class="empty-state">Belum ada data barang yang tercatat untuk ruangan ini.</div>
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
                                                            <td>
                                                                <div class="inventory-name">{{ ucfirst($row->nama_barang) }}</div>
                                                            </td>
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
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="room-card">
                        <div class="empty-state">Akunmu belum memiliki penugasan kelas atau ruangan aktif. Hubungi wali kelas atau pengelola sistem untuk menambahkan akses.</div>
                    </article>
                @endforelse
            </section>
            </div>
        </main>
    </div>
    @include('chatbot')
</body>
</html>

