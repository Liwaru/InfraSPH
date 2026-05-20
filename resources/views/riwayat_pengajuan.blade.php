<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Pengajuan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/riwayat_pengajuan.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="history-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengguna' }}</div>
                    <h1 class="hero-title">Riwayat Pengajuan</h1>
                    <p class="hero-subtitle">
                        Pantau semua permintaan yang pernah kamu ajukan, lihat status terbarunya, dan buka detail untuk mengetahui progres persetujuan.
                    </p>
                </section>

                @if (session('success'))
                    <section class="filter-card alert-success-card">
                        {{ session('success') }}
                    </section>
                @endif

                @if (session('error'))
                    <section class="filter-card alert-error-card">
                        {{ session('error') }}
                    </section>
                @endif

                <section class="filter-card">
                    <div class="filter-row">
                        <button type="button" class="filter-chip active" data-filter="all">Semua ({{ $statusCounts['all'] }})</button>
                        <button type="button" class="filter-chip" data-filter="process">Diproses ({{ $statusCounts['process'] }})</button>
                        <button type="button" class="filter-chip" data-filter="approved">Disetujui ({{ $statusCounts['approved'] }})</button>
                        <button type="button" class="filter-chip" data-filter="rejected">Ditolak ({{ $statusCounts['rejected'] }})</button>
                    </div>
                </section>

                @if ($requests->isEmpty())
                    <section class="empty-card">
                        <div class="empty-title">Belum ada pengajuan</div>
                        <div class="empty-copy">Silakan ajukan permintaan terlebih dahulu melalui menu <strong>Ajukan Permintaan</strong>.</div>
                    </section>
                @else
                    <section class="table-card">
                        <div class="table-wrap">
                            <table class="request-table mobile-card-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests as $request)
                                        <tr class="request-record" data-status="{{ $request['status_key'] }}">
                                            <td data-label="Tanggal">
                                                <div>{{ $request['tanggal_label'] }}</div>
                                                <div class="request-code">{{ $request['kode_permintaan'] }}</div>
                                            </td>
                                            <td data-label="Jenis">{{ $request['jenis'] }}</td>
                                            <td data-label="Barang" class="request-item">{{ $request['barang_ringkas'] }}</td>
                                            <td data-label="Jumlah">{{ $request['jumlah_ringkas'] }}</td>
                                            <td data-label="Status"><span class="badge {{ $request['status_class'] }}">{{ $request['status'] }}</span></td>
                                            <td data-label="Aksi">
                                                <form method="POST" action="{{ route('requests.destroy', $request['id_permintaan']) }}" class="delete-request-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-delete">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            </div>
        </main>
    </div>
    @include('chatbot')

    <script>
        (function () {
            const filterButtons = document.querySelectorAll('.filter-chip');
            const records = document.querySelectorAll('.request-record');
            const deleteForms = document.querySelectorAll('.delete-request-form');

            filterButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const filter = button.getAttribute('data-filter');

                    filterButtons.forEach(function (item) {
                        item.classList.toggle('active', item === button);
                    });

                    records.forEach(function (row) {
                        const status = row.getAttribute('data-status');
                        const visible = filter === 'all' || status === filter;

                        row.style.display = visible ? '' : 'none';
                    });
                });
            });

            deleteForms.forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    const confirmed = window.confirm('Yakin ingin menghapus pengajuan ini?');

                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        })();
    </script>
</body>
</html>

