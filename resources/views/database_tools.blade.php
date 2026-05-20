<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Database | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/database_tools.css') }}">
</head>
<body>
<div class="app-shell" id="appShell">
    @include('header')

    <main class="database-page">
        <div class="page-shell">
            <section class="hero-card">
                <div class="eyebrow"><i class="bi bi-database-fill-gear"></i> Database</div>
                <h1 class="hero-title">Backup, reset, dan impor database.</h1>
                <p class="hero-subtitle">Gunakan panel ini untuk menyimpan salinan SQL, membangun ulang database dari migrasi dan seeder, atau mengimpor file SQL ke database aktif.</p>
            </section>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <section class="summary-grid">
                <div class="summary-card">
                    <div class="summary-label">Koneksi</div>
                    <div class="summary-value">{{ $databaseInfo['connection'] }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Driver</div>
                    <div class="summary-value">{{ $databaseInfo['driver'] }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Jumlah Tabel</div>
                    <div class="summary-value">{{ number_format($databaseInfo['table_count']) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Backup Terakhir</div>
                    <div class="summary-value">
                        {{ $latestBackup ? \Carbon\Carbon::createFromTimestamp($latestBackup['created_at'])->format('d M Y H:i') : '-' }}
                    </div>
                </div>
            </section>

            <section class="tools-grid">
                <article class="tool-card">
                    <div class="tool-head">
                        <div class="tool-icon"><i class="bi bi-cloud-arrow-down-fill"></i></div>
                        <div>
                            <h2 class="tool-title">Backup Database</h2>
                            <p class="tool-copy">Unduh seluruh struktur tabel dan isi data sebagai file SQL.</p>
                        </div>
                    </div>
                    @if ($latestBackup)
                        <div class="hint">Backup tersimpan: {{ $latestBackup['name'] }} ({{ number_format($latestBackup['size'] / 1024, 1) }} KB)</div>
                    @endif
                    <form method="POST" action="{{ route('superadmin.database.backup') }}" class="tool-form">
                        @csrf
                        <button type="submit" class="btn"><i class="bi bi-download"></i> Download Backup</button>
                    </form>
                </article>

                <article class="tool-card">
                    <div class="tool-head">
                        <div class="tool-icon"><i class="bi bi-arrow-repeat"></i></div>
                        <div>
                            <h2 class="tool-title">Reset Database</h2>
                            <p class="tool-copy">Hapus semua tabel lalu jalankan ulang migrasi dan seeder bawaan aplikasi.</p>
                        </div>
                    </div>
                    <div class="hint">Ketik <strong>RESET DATABASE</strong> sebelum menekan tombol reset.</div>
                    <form method="POST" action="{{ route('superadmin.database.reset') }}" class="tool-form" onsubmit="return confirm('Reset database akan menghapus data saat ini. Lanjutkan?');">
                        @csrf
                        <label for="reset_confirmation">Konfirmasi Reset</label>
                        <input id="reset_confirmation" type="text" name="confirmation" placeholder="RESET DATABASE" autocomplete="off">
                        <button type="submit" class="btn danger"><i class="bi bi-exclamation-triangle-fill"></i> Reset Database</button>
                    </form>
                </article>

                <article class="tool-card">
                    <div class="tool-head">
                        <div class="tool-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                        <div>
                            <h2 class="tool-title">Impor Database</h2>
                            <p class="tool-copy">Unggah file SQL dan jalankan isinya ke database yang sedang aktif.</p>
                        </div>
                    </div>
                    <div class="hint">Ketik <strong>IMPORT DATABASE</strong>. Buat backup dulu sebelum impor jika data lama masih diperlukan.</div>
                    <form method="POST" action="{{ route('superadmin.database.import') }}" class="tool-form" enctype="multipart/form-data" onsubmit="return confirm('Impor SQL dapat menimpa data. Lanjutkan?');">
                        @csrf
                        <label for="database_file">File SQL</label>
                        <input id="database_file" type="file" name="database_file" accept=".sql,.txt">
                        <label for="import_confirmation">Konfirmasi Impor</label>
                        <input id="import_confirmation" type="text" name="confirmation" placeholder="IMPORT DATABASE" autocomplete="off">
                        <button type="submit" class="btn secondary"><i class="bi bi-upload"></i> Impor Database</button>
                    </form>
                </article>
            </section>
        </div>
    </main>
</div>
</body>
</html>

