<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Barang | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/data_inventaris.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="superadmin-items-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div>
                        <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengelola Sistem' }}</div>
                        <h1 class="hero-title">Data Barang</h1>
                        <p class="hero-subtitle">Pantau seluruh barang dari semua kelas, lab, dan kantor guru dalam satu tampilan global inventaris yang lebih cepat dibaca.</p>
                    </div>
                </section>

                <section class="summary-grid">
                    <article class="summary-card">
                        <div class="summary-label">Total Barang</div>
                        <div class="summary-value">{{ number_format($summary['total_barang']) }}</div>
                        <div class="summary-note">Total unit barang dari seluruh ruangan.</div>
                    </article>
                    <article class="summary-card is-accent">
                        <div class="summary-label">Barang Baik</div>
                        <div class="summary-value">{{ number_format($summary['barang_baik']) }}</div>
                        <div class="summary-note">Unit barang yang masih dalam kondisi baik.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Barang Rusak</div>
                        <div class="summary-value">{{ number_format($summary['barang_rusak']) }}</div>
                        <div class="summary-note">Unit barang yang saat ini tercatat rusak.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Perlu Perbaikan</div>
                        <div class="summary-value">{{ number_format($summary['barang_perlu_perbaikan']) }}</div>
                        <div class="summary-note">Entri inventaris dengan stok baik dan rusak sekaligus.</div>
                    </article>
                </section>

                <section class="filter-card">
                    <form method="GET" action="{{ route('superadmin.items') }}" class="filter-form">
                        <div class="filter-field">
                            <label for="itemSearch">Nama Barang</label>
                            <input id="itemSearch" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama barang">
                        </div>
                        <div class="filter-field">
                            <label for="itemCategory">Kategori</label>
                            <select id="itemCategory" name="category">
                                <option value="semua" @selected($filters['category'] === 'semua')>Semua Kategori</option>
                                @foreach ($categoryOptions as $option)
                                    <option value="{{ $option->id_kategori_barang }}" @selected($filters['category'] === (string) $option->id_kategori_barang)>{{ ucfirst($option->nama_kategori) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label for="itemRoom">Ruangan</label>
                            <select id="itemRoom" name="room">
                                <option value="semua" @selected($filters['room'] === 'semua')>Semua Ruangan</option>
                                @foreach ($roomOptions as $option)
                                    <option value="{{ $option->id_ruangan }}" @selected($filters['room'] === (string) $option->id_ruangan)>{{ $option->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label for="itemRoomType">Jenis Ruangan</label>
                            <select id="itemRoomType" name="room_type">
                                <option value="semua" @selected($filters['room_type'] === 'semua')>Semua Jenis</option>
                                @foreach ($roomTypeOptions as $option)
                                    <option value="{{ $option }}" @selected($filters['room_type'] === $option)>{{ $option === 'kantor_guru' ? 'Kantor Guru' : ucfirst($option) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label for="itemCondition">Kondisi</label>
                            <select id="itemCondition" name="condition">
                                <option value="semua" @selected($filters['condition'] === 'semua')>Semua Kondisi</option>
                                <option value="baik" @selected($filters['condition'] === 'baik')>Baik</option>
                                <option value="rusak" @selected($filters['condition'] === 'rusak')>Rusak</option>
                                <option value="perlu_perbaikan" @selected($filters['condition'] === 'perlu_perbaikan')>Perlu Perbaikan</option>
                            </select>
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="filter-btn">
                                <i class="bi bi-funnel-fill"></i>
                                <span>Terapkan</span>
                            </button>
                            <a href="{{ route('superadmin.items') }}" class="filter-link">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </section>

                @if (session('success') || session('error') || $errors->any())
                    <div class="feedback-stack">
                        @if (session('success'))
                            <div class="success-banner">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="error-banner">{{ session('error') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="error-banner">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <section class="table-card">
                    <div class="table-header">
                        <div>
                            <div class="table-title">Daftar Barang Global</div>
                            <div class="table-subtitle">Menampilkan {{ number_format($items->total()) }} data barang dari seluruh ruangan berdasarkan filter aktif.</div>
                        </div>
                        <div class="table-header-actions">
                            <button type="button" class="action-btn js-open-modal" data-modal="copy-items">
                                <i class="bi bi-copy"></i>
                                <span>Salin Barang</span>
                            </button>
                            <button type="button" class="action-btn primary js-open-modal" data-modal="create-item">
                                <i class="bi bi-plus-square"></i>
                                <span>Tambah Barang</span>
                            </button>
                        </div>
                    </div>

                    @if (count($itemRows) === 0)
                        <div class="empty-state">Belum ada data barang yang cocok dengan filter saat ini.</div>
                    @else
                        <div class="table-wrap">
                            <table class="mobile-card-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Ruangan</th>
                                        <th>Jenis Ruangan</th>
                                        <th>Jumlah</th>
                                        <th>Kondisi</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itemRows as $index => $item)
                                        @php
                                            $number = ($items->firstItem() ?? 1) + $index;
                                            $roomTypeClass = $item['jenis_ruangan_raw'] === 'kelas'
                                                ? 'kelas'
                                                : ($item['jenis_ruangan_raw'] === 'kantor_guru' ? 'kantor' : 'lab');
                                        @endphp
                                        <tr>
                                            <td data-label="No">{{ $number }}</td>
                                            <td data-label="Nama Barang">
                                                <div class="item-name">{{ $item['nama_barang'] }}</div>
                                                <div class="item-meta">ID Inventaris: {{ $item['id_inventaris_ruangan'] }}</div>
                                            </td>
                                            <td data-label="Kategori">{{ $item['nama_kategori'] }}</td>
                                            <td data-label="Ruangan">
                                                <div class="item-name">{{ $item['nama_ruangan'] }}</div>
                                                <div class="item-meta">{{ $item['kode_ruangan'] }}</div>
                                            </td>
                                            <td data-label="Jenis Ruangan"><span class="pill {{ $roomTypeClass }}">{{ $item['jenis_ruangan'] }}</span></td>
                                            <td data-label="Jumlah">
                                                <div class="qty-stack">
                                                    <strong>{{ number_format($item['jumlah_total']) }} unit</strong>
                                                    <span class="item-meta">{{ number_format($item['jumlah_baik']) }} baik, {{ number_format($item['jumlah_rusak']) }} rusak</span>
                                                </div>
                                            </td>
                                            <td data-label="Kondisi">
                                                <span class="pill {{ $item['kondisi_class'] }}">{{ $item['kondisi_label'] }}</span>
                                                <div class="condition-copy">{{ $item['kondisi_note'] }}</div>
                                            </td>
                                            <td data-label="Tanggal Masuk">
                                                <div class="item-meta">{{ $item['tanggal_masuk'] }}</div>
                                            </td>
                                            <td data-label="Aksi">
                                                <div class="action-group">
                                                    <button type="button" class="row-action js-open-modal" data-modal="detail-item-{{ $item['id_inventaris_ruangan'] }}">
                                                        <i class="bi bi-eye"></i>
                                                        <span>Detail</span>
                                                    </button>
                                                    <button type="button" class="row-action js-open-modal" data-modal="edit-item-{{ $item['id_inventaris_ruangan'] }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                        <span>Edit</span>
                                                    </button>
                                                    <form method="POST" action="{{ route('superadmin.items.delete', $item['id_inventaris_ruangan']) }}" onsubmit="return confirm('Hapus data barang ini?');">
                                                        @csrf
                                                        <input type="hidden" name="q" value="{{ $filters['q'] }}">
                                                        <input type="hidden" name="category_filter" value="{{ $filters['category'] }}">
                                                        <input type="hidden" name="room_filter" value="{{ $filters['room'] }}">
                                                        <input type="hidden" name="room_type_filter" value="{{ $filters['room_type'] }}">
                                                        <input type="hidden" name="condition_filter" value="{{ $filters['condition'] }}">
                                                        <button type="submit" class="row-action danger">
                                                            <i class="bi bi-trash3"></i>
                                                            <span>Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($items->hasPages())
                            <div class="pagination-wrap">
                                <div class="pagination">
                                    <span class="pagination-info">Halaman {{ $items->currentPage() }} dari {{ $items->lastPage() }}</span>

                                    @if ($items->onFirstPage())
                                        <span class="pagination-link disabled"><i class="bi bi-chevron-left"></i></span>
                                    @else
                                        <a href="{{ $items->previousPageUrl() }}" class="pagination-link" aria-label="Halaman sebelumnya">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    @endif

                                    @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                                        @if ($page === $items->currentPage())
                                            <span class="pagination-current">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if ($items->hasMorePages())
                                        <a href="{{ $items->nextPageUrl() }}" class="pagination-link" aria-label="Halaman berikutnya">
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

    <div class="modal-shell" id="modal-create-item" aria-hidden="true">
        <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateItemTitle">
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="modalCreateItemTitle">Tambah Barang</div>
                    <div class="modal-subtitle">Tambahkan barang baru ke ruangan tertentu. Nama barang yang sama bisa dipakai di ruangan lain sebagai entri terpisah.</div>
                </div>
                <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('superadmin.items.store') }}" class="modal-form">
                @csrf
                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                <input type="hidden" name="category_filter" value="{{ $filters['category'] }}">
                <input type="hidden" name="room_filter" value="{{ $filters['room'] }}">
                <input type="hidden" name="room_type_filter" value="{{ $filters['room_type'] }}">
                <input type="hidden" name="condition_filter" value="{{ $filters['condition'] }}">

                <div class="field-grid">
                    <div class="field-group">
                        <label for="createItemName">Nama Barang</label>
                        <input id="createItemName" type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: Kursi Siswa" required>
                    </div>
                    <div class="field-group">
                        <label for="createItemCategory">Kategori</label>
                        <select id="createItemCategory" name="id_kategori_barang" required>
                            <option value="" disabled @selected(old('id_kategori_barang') === null || old('id_kategori_barang') === '')>Pilih kategori</option>
                            @foreach ($categoryOptions as $option)
                                <option value="{{ $option->id_kategori_barang }}" @selected((string) old('id_kategori_barang') === (string) $option->id_kategori_barang)>{{ ucfirst($option->nama_kategori) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field-grid is-room-picker">
                    <div class="field-group">
                        <label>Ruangan Tujuan</label>
                        <div class="room-checklist">
                            @foreach ($roomOptions as $option)
                                <label class="room-check">
                                    <input type="checkbox" name="id_ruangan[]" value="{{ $option->id_ruangan }}" @checked(in_array((string) $option->id_ruangan, (array) old('id_ruangan', []), true))>
                                    <span>
                                        <span class="room-check-name">{{ $option->nama_ruangan }}</span>
                                        <span class="room-check-meta">{{ $option->jenis_ruangan === 'kantor_guru' ? 'Kantor Guru' : ucfirst($option->jenis_ruangan) }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="stacked-fields">
                        <div class="field-group">
                            <label for="createItemGood">Jumlah Baik</label>
                            <input id="createItemGood" type="number" min="0" name="jumlah_baik" value="{{ old('jumlah_baik', 0) }}" required>
                        </div>
                        <div class="field-group">
                            <label for="createItemDamaged">Jumlah Rusak</label>
                            <input id="createItemDamaged" type="number" min="0" name="jumlah_rusak" value="{{ old('jumlah_rusak', 0) }}" required>
                        </div>
                        <div class="field-group">
                            <label>Catatan</label>
                            <input type="text" value="Tanggal masuk belum tersedia di database inventaris." disabled>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="ghost-btn js-close-modal">Batal</button>
                    <button type="submit" class="submit-btn">
                        <i class="bi bi-check-circle"></i>
                        <span>Simpan Barang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-shell" id="modal-copy-items" aria-hidden="true">
        <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCopyItemsTitle">
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="modalCopyItemsTitle">Salin Barang Antar Ruangan</div>
                    <div class="modal-subtitle">Pilih satu ruangan sumber, lalu centang ruangan tujuan. Barang yang sudah ada di tujuan akan disamakan jumlahnya dengan sumber.</div>
                </div>
                <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('superadmin.items.copy') }}" class="modal-form">
                @csrf
                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                <input type="hidden" name="category_filter" value="{{ $filters['category'] }}">
                <input type="hidden" name="room_filter" value="{{ $filters['room'] }}">
                <input type="hidden" name="room_type_filter" value="{{ $filters['room_type'] }}">
                <input type="hidden" name="condition_filter" value="{{ $filters['condition'] }}">

                <div class="field-grid is-room-picker">
                    <div class="field-group">
                        <label>Ruangan Tujuan</label>
                        <div class="room-checklist">
                            @foreach ($roomOptions as $option)
                                <label class="room-check">
                                    <input type="checkbox" name="target_room_ids[]" value="{{ $option->id_ruangan }}" @checked(in_array((string) $option->id_ruangan, (array) old('target_room_ids', []), true))>
                                    <span>
                                        <span class="room-check-name">{{ $option->nama_ruangan }}</span>
                                        <span class="room-check-meta">{{ $option->jenis_ruangan === 'kantor_guru' ? 'Kantor Guru' : ucfirst($option->jenis_ruangan) }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="field-group">
                        <label for="copySourceRoom">Ruangan Sumber</label>
                        <select id="copySourceRoom" name="source_room_id" required>
                            <option value="" disabled @selected(old('source_room_id') === null || old('source_room_id') === '')>Pilih ruangan sumber</option>
                            @foreach ($roomOptions as $option)
                                <option value="{{ $option->id_ruangan }}" @selected((string) old('source_room_id') === (string) $option->id_ruangan)>{{ $option->nama_ruangan }} ({{ $option->jenis_ruangan === 'kantor_guru' ? 'Kantor Guru' : ucfirst($option->jenis_ruangan) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="ghost-btn js-close-modal">Batal</button>
                    <button type="submit" class="submit-btn">
                        <i class="bi bi-copy"></i>
                        <span>Salin Barang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($itemRows as $item)
        <div class="modal-shell" id="modal-detail-item-{{ $item['id_inventaris_ruangan'] }}" aria-hidden="true">
            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDetailItemTitle-{{ $item['id_inventaris_ruangan'] }}">
                <div class="modal-header">
                    <div>
                        <div class="modal-title" id="modalDetailItemTitle-{{ $item['id_inventaris_ruangan'] }}">Detail Barang</div>
                        <div class="modal-subtitle">Ringkasan data barang dan lokasi inventaris berdasarkan entri yang dipilih.</div>
                    </div>
                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="detail-grid">
                    <div class="detail-card">
                        <div class="detail-label">Nama Barang</div>
                        <div class="detail-value">{{ $item['nama_barang'] }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Kategori</div>
                        <div class="detail-value">{{ $item['nama_kategori'] }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Ruangan</div>
                        <div class="detail-value">{{ $item['nama_ruangan'] }}</div>
                        <div class="detail-value muted">{{ $item['kode_ruangan'] }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Jenis Ruangan</div>
                        <div class="detail-value">{{ $item['jenis_ruangan'] }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Jumlah Baik</div>
                        <div class="detail-value">{{ number_format($item['jumlah_baik']) }} unit</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Jumlah Rusak</div>
                        <div class="detail-value">{{ number_format($item['jumlah_rusak']) }} unit</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Kondisi</div>
                        <div class="detail-value">{{ $item['kondisi_label'] }}</div>
                        <div class="detail-value muted">{{ $item['kondisi_note'] }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Tanggal Masuk</div>
                        <div class="detail-value">{{ $item['tanggal_masuk'] }}</div>
                        <div class="detail-value muted">Kolom tanggal belum tersedia pada tabel inventaris saat ini.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-shell" id="modal-edit-item-{{ $item['id_inventaris_ruangan'] }}" aria-hidden="true">
            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditItemTitle-{{ $item['id_inventaris_ruangan'] }}">
                <div class="modal-header">
                    <div>
                        <div class="modal-title" id="modalEditItemTitle-{{ $item['id_inventaris_ruangan'] }}">Edit Barang</div>
                        <div class="modal-subtitle">Perbarui nama, kategori, ruangan, dan jumlah barang untuk entri inventaris ini.</div>
                    </div>
                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('superadmin.items.update', $item['id_inventaris_ruangan']) }}" class="modal-form">
                    @csrf
                    <input type="hidden" name="q" value="{{ $filters['q'] }}">
                    <input type="hidden" name="category_filter" value="{{ $filters['category'] }}">
                    <input type="hidden" name="room_filter" value="{{ $filters['room'] }}">
                    <input type="hidden" name="room_type_filter" value="{{ $filters['room_type'] }}">
                    <input type="hidden" name="condition_filter" value="{{ $filters['condition'] }}">

                    <div class="field-grid">
                        <div class="field-group">
                            <label for="editItemName-{{ $item['id_inventaris_ruangan'] }}">Nama Barang</label>
                            <input id="editItemName-{{ $item['id_inventaris_ruangan'] }}" type="text" name="nama_barang" value="{{ old('nama_barang', $item['nama_barang']) }}" required>
                        </div>
                        <div class="field-group">
                            <label for="editItemCategory-{{ $item['id_inventaris_ruangan'] }}">Kategori</label>
                            <select id="editItemCategory-{{ $item['id_inventaris_ruangan'] }}" name="id_kategori_barang" required>
                                @foreach ($categoryOptions as $option)
                                    <option value="{{ $option->id_kategori_barang }}" @selected((string) old('id_kategori_barang', $item['id_kategori_barang']) === (string) $option->id_kategori_barang)>{{ ucfirst($option->nama_kategori) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field-grid">
                        <div class="field-group">
                            <label for="editItemRoom-{{ $item['id_inventaris_ruangan'] }}">Ruangan</label>
                            <select id="editItemRoom-{{ $item['id_inventaris_ruangan'] }}" name="id_ruangan" required>
                                @foreach ($roomOptions as $option)
                                    <option value="{{ $option->id_ruangan }}" @selected((string) old('id_ruangan', $item['id_ruangan']) === (string) $option->id_ruangan)>{{ $option->nama_ruangan }} ({{ $option->jenis_ruangan === 'kantor_guru' ? 'Kantor Guru' : ucfirst($option->jenis_ruangan) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="editItemGood-{{ $item['id_inventaris_ruangan'] }}">Jumlah Baik</label>
                            <input id="editItemGood-{{ $item['id_inventaris_ruangan'] }}" type="number" min="0" name="jumlah_baik" value="{{ old('jumlah_baik', $item['jumlah_baik']) }}" required>
                        </div>
                    </div>

                    <div class="field-grid">
                        <div class="field-group">
                            <label for="editItemDamaged-{{ $item['id_inventaris_ruangan'] }}">Jumlah Rusak</label>
                            <input id="editItemDamaged-{{ $item['id_inventaris_ruangan'] }}" type="number" min="0" name="jumlah_rusak" value="{{ old('jumlah_rusak', $item['jumlah_rusak']) }}" required>
                        </div>
                        <div class="field-group">
                            <label>Catatan</label>
                            <input type="text" value="Jika nama sama dan ruangan sama, data akan diperbarui pada entri yang cocok." disabled>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                        <button type="submit" class="submit-btn">
                            <i class="bi bi-check-circle"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        (function () {
            const modalShells = document.querySelectorAll('.modal-shell');
            const openButtons = document.querySelectorAll('.js-open-modal');
            const closeButtons = document.querySelectorAll('.js-close-modal');

            function closeAllModals() {
                modalShells.forEach((modal) => {
                    modal.classList.remove('is-open');
                    modal.setAttribute('aria-hidden', 'true');
                });
                window.InfraSPHScrollLock?.unlock();
                document.body.style.overflow = '';
            }

            function openModal(modalName) {
                const target = document.getElementById('modal-' + modalName);
                if (!target) {
                    return;
                }

                closeAllModals();
                target.classList.add('is-open');
                target.setAttribute('aria-hidden', 'false');
                window.InfraSPHScrollLock?.lock();
                document.body.style.overflow = 'hidden';
            }

            openButtons.forEach((button) => {
                button.addEventListener('click', function () {
                    openModal(this.dataset.modal);
                });
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', closeAllModals);
            });

            modalShells.forEach((modal) => {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeAllModals();
                    }
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeAllModals();
                }
            });

            @if (session('modal'))
                openModal(@json(session('modal')));
            @endif
        })();
    </script>
</body>
</html>

