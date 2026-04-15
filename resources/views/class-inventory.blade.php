<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas Saya | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --brand-orange: #ff5900;
            --brand-orange-dark: #e14f00;
            --sidebar-top: #ff7a21;
            --sidebar-bottom: #ff5900;
            --sidebar-text: #fff6f1;
            --sidebar-hover: rgba(255, 255, 255, 0.14);
            --sidebar-active: rgba(255, 255, 255, 0.92);
            --sidebar-border: rgba(255, 255, 255, 0.18);
            --text-dark: #1f2937;
            --page-bg: #fff8f4;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 320px;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, var(--sidebar-top), var(--sidebar-bottom));
            color: var(--sidebar-text);
            padding: 1.25rem 1rem 1rem;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 14px 0 34px rgba(225, 79, 0, 0.18);
        }

        .sidebar-brand,
        .sidebar-brand-main,
        .sidebar-user-info,
        .sidebar-nav a,
        .sidebar-logout-btn {
            display: flex;
            align-items: center;
        }

        .sidebar-brand {
            gap: 1rem;
            padding: 0.7rem 0.55rem 1rem;
            margin-bottom: 0.85rem;
            border-bottom: 1px solid var(--sidebar-border);
            justify-content: space-between;
        }

        .sidebar-brand-link { flex: 1 1 auto; text-decoration: none; }
        .sidebar-brand-main { gap: 1.25rem; }
        .sidebar-brand-logo { width: 62px; height: 62px; display: inline-flex; align-items: center; justify-content: center; }
        .sidebar-brand img { width: 62px; height: auto; object-fit: contain; transform: scale(1.18); }
        .sidebar-brand-text { font-size: 1.6rem; font-weight: 800; color: #ffffff; }
        .sidebar-toggle { width: 44px; height: 44px; border: none; border-radius: 14px; background: rgba(255,255,255,0.14); color: #fff; }
        .sidebar-user-info { gap: 0.85rem; margin: 0.2rem 0 1rem; padding: 0.9rem 0.85rem; background: rgba(255,255,255,0.13); border: 1px solid rgba(255,255,255,0.16); border-radius: 18px; }
        .sidebar-avatar { width: 46px; height: 46px; border-radius: 50%; display:flex; align-items:center; justify-content:center; background: rgba(255,255,255,0.24); color:#fff; font-weight:800; border:2px solid rgba(255,255,255,0.35); }
        .sidebar-user-name { color:#fff; font-size:0.95rem; font-weight:700; }
        .sidebar-user-role { color: rgba(255,255,255,0.85); font-size:0.78rem; margin-top:0.15rem; }
        .sidebar-nav { list-style:none; display:grid; gap:0.35rem; }
        .sidebar-nav a { gap:0.8rem; padding:0.9rem 0.95rem; border-radius:16px; color:rgba(255,255,255,0.94); text-decoration:none; font-size:0.95rem; font-weight:600; }
        .sidebar-nav a.active { background: var(--sidebar-active); color: var(--brand-orange-dark); }
        .sidebar-nav i, .sidebar-logout-btn i { font-size:1.05rem; width:1.2rem; text-align:center; }
        .sidebar-logout { margin-top:auto; padding-top:1rem; }
        .sidebar-logout-btn { width:100%; justify-content:center; gap:0.75rem; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.12); color:#fff; border-radius:18px; padding:0.95rem 1rem; font-size:0.95rem; font-weight:700; cursor:pointer; }

        .content-area {
            margin-left: 320px;
            min-height: 100vh;
            padding: 2rem 1.6rem 2.5rem;
        }

        .page-shell { max-width: 1280px; }
        .hero-card, .room-card {
            background: #ffffff;
            border: 1px solid #f3e3db;
            border-radius: 28px;
            box-shadow: 0 18px 38px -28px rgba(31, 41, 55, 0.24);
        }

        .hero-card {
            padding: 1.6rem 1.7rem;
            margin-bottom: 1.4rem;
            background: linear-gradient(135deg, rgba(255, 89, 0, 0.08), rgba(255, 89, 0, 0.02));
        }

        .eyebrow {
            display:inline-flex;
            align-items:center;
            gap:0.45rem;
            padding:0.45rem 0.8rem;
            border-radius:999px;
            background: rgba(255, 89, 0, 0.12);
            color: var(--brand-orange);
            font-size:0.82rem;
            font-weight:700;
            margin-bottom:0.95rem;
        }

        .hero-title { font-size: clamp(1.8rem, 2.6vw, 2.4rem); color: var(--brand-orange); margin-bottom: 0.7rem; letter-spacing: -0.04em; }
        .hero-subtitle { color:#5b6472; line-height:1.7; max-width:760px; }
        .room-stack { display:grid; gap:1.2rem; }
        .room-card { padding: 1.35rem 1.3rem; }
        .room-header { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; margin-bottom:1rem; }
        .room-title { font-size:1.15rem; font-weight:800; color: var(--brand-orange); }
        .room-meta { color:#667085; font-size:0.92rem; line-height:1.6; }
        .room-badge { display:inline-flex; align-items:center; gap:0.45rem; padding:0.55rem 0.8rem; border-radius:999px; background:#fff3eb; color:var(--brand-orange-dark); font-size:0.82rem; font-weight:700; }
        .inventory-table-wrap { overflow-x:auto; }
        .inventory-table { width:100%; border-collapse:collapse; min-width:720px; }
        .inventory-table th, .inventory-table td { text-align:left; padding:0.9rem 0.85rem; border-bottom:1px solid #f5e7de; vertical-align:top; }
        .inventory-table th { color:#6b7280; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.06em; }
        .inventory-table td { color:#344054; font-size:0.95rem; }
        .inventory-table tbody tr:last-child td { border-bottom:none; }
        .inventory-count { font-weight:700; }
        .inventory-count.good { color:#15803d; }
        .inventory-count.bad { color:#c2410c; }
        .empty-state { padding: 1rem 0.25rem 0.25rem; color:#667085; }

        @media (max-width: 860px) {
            .sidebar { position: static; width: 100%; height:auto; border-radius:0 0 24px 24px; }
            .content-area { margin-left: 0; padding:1.2rem 1rem 2rem; }
        }
    </style>
</head>
<body>
    @include('header')

    <main class="content-area">
        <div class="page-shell">
            <section class="hero-card">
                <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengguna' }}</div>
                <h1 class="hero-title">Data Barang Kelas</h1>
                <p class="hero-subtitle">
                    Halaman ini menampilkan seluruh data barang dari kelas atau ruangan yang ditugaskan pada akunmu. Untuk ketua kelas dan wali kelas, data yang tampil tetap terbatas pada lingkup kelas masing-masing.
                </p>
            </section>

            <section class="room-stack">
                @forelse ($assignments as $assignment)
                    @php
                        $inventoryRows = $inventoryByRoom->get($assignment->id_ruangan, collect());
                    @endphp
                    <article class="room-card">
                        <div class="room-header">
                            <div>
                                <div class="room-title">{{ $assignment->nama_ruangan }}</div>
                                <div class="room-meta">
                                    Kode ruangan: {{ $assignment->kode_ruangan }}<br>
                                    Jenis ruangan: {{ ucfirst($assignment->jenis_ruangan) }}
                                </div>
                            </div>
                            <div class="room-badge">
                                <i class="bi bi-door-open-fill"></i>
                                {{ str_replace('_', ' ', ucfirst($assignment->peran_ruangan)) }}
                            </div>
                        </div>

                        @if ($inventoryRows->isEmpty())
                            <div class="empty-state">Belum ada data barang yang tercatat untuk ruangan ini.</div>
                        @else
                            <div class="inventory-table-wrap">
                                <table class="inventory-table">
                                    <thead>
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Kondisi Baik</th>
                                            <th>Kondisi Rusak</th>
                                            <th>Satuan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inventoryRows as $row)
                                            <tr>
                                                <td>{{ ucfirst($row->nama_barang) }}</td>
                                                <td><span class="inventory-count good">{{ $row->jumlah_baik }}</span></td>
                                                <td><span class="inventory-count bad">{{ $row->jumlah_rusak }}</span></td>
                                                <td>{{ $row->satuan }}</td>
                                                <td>{{ $row->keterangan ?: '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </article>
                @empty
                    <article class="room-card">
                        <div class="empty-state">Akunmu belum memiliki penugasan kelas atau ruangan aktif. Hubungi superadmin untuk menambahkan akses.</div>
                    </article>
                @endforelse
            </section>
        </div>
    </main>
</body>
</html>
