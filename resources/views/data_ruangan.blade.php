<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Ruangan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/data_ruangan.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="superadmin-rooms-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div>
                        <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengelola Sistem' }}</div>
                        <h1 class="hero-title">Data Ruangan</h1>
                        <p class="hero-subtitle">Kelola identitas ruangan, penanggung jawab, dan ringkasan inventaris ruangan dari satu halaman superadmin yang lebih rapi.</p>
                    </div>
                </section>

                <section class="summary-grid">
                    <article class="summary-card">
                        <div class="summary-label">Total Ruangan</div>
                        <div class="summary-value">{{ number_format($summary['total_ruangan']) }}</div>
                        <div class="summary-note">Semua ruangan yang tercatat di sistem.</div>
                    </article>
                    <article class="summary-card is-accent">
                        <div class="summary-label">Total Kelas</div>
                        <div class="summary-value">{{ number_format($summary['total_kelas']) }}</div>
                        <div class="summary-note">Ruangan dengan jenis kelas.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Total Laboratorium</div>
                        <div class="summary-value">{{ number_format($summary['total_laboratorium']) }}</div>
                        <div class="summary-note">Laboratorium dan ruang praktik sejenis.</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Total Inventaris</div>
                        <div class="summary-value">{{ number_format($summary['total_inventaris']) }}</div>
                        <div class="summary-note">Akumulasi unit barang dari seluruh ruangan.</div>
                    </article>
                </section>

                <section class="filter-card">
                    <form method="GET" action="{{ route('superadmin.rooms') }}" class="filter-form">
                        <div class="filter-field">
                            <label for="roomSearch">Nama Ruangan</label>
                            <input id="roomSearch" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama ruangan">
                        </div>
                        <div class="filter-field">
                            <label for="roomType">Jenis Ruangan</label>
                            <select id="roomType" name="type">
                                <option value="semua" @selected($filters['type'] === 'semua')>Semua Jenis</option>
                                @foreach ($typeOptions as $option)
                                    <option value="{{ $option }}" @selected($filters['type'] === $option)>{{ ucfirst($option) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label for="roomUnit">Kelas</label>
                            <select id="roomUnit" name="unit">
                                <option value="semua" @selected($filters['unit'] === 'semua')>Semua Unit</option>
                                @foreach ($unitOptions as $option)
                                    <option value="{{ $option }}" @selected($filters['unit'] === $option)>{{ strtoupper($option) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="filter-btn">
                                <i class="bi bi-funnel-fill"></i>
                                <span>Terapkan</span>
                            </button>
                            <a href="{{ route('superadmin.rooms') }}" class="filter-link">
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
                            <div class="table-title">Daftar Ruangan</div>
                            <div class="table-subtitle">Menampilkan {{ number_format($rooms->total()) }} ruangan berdasarkan filter aktif.</div>
                        </div>
                        <div class="table-header-actions">
                            <button type="button" class="action-btn primary js-open-modal" data-modal="create-room">
                                <i class="bi bi-building-add"></i>
                                <span>Tambah Ruangan</span>
                            </button>
                        </div>
                    </div>

                    @if (count($roomRows) === 0)
                        <div class="empty-state">Belum ada data ruangan yang cocok dengan filter saat ini.</div>
                    @else
                        <div class="table-wrap">
                            <table class="mobile-card-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Ruangan</th>
                                        <th>Kode</th>
                                        <th>Jenis</th>
                                        <th>Kelas</th>
                                        <th>Wali / Penanggung Jawab</th>
                                        <th>Ketua Kelas</th>
                                        <th>Total Inventaris</th>
                                        <th>Kondisi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roomRows as $index => $room)
                                        @php
                                            $number = ($rooms->firstItem() ?? 1) + $index;
                                            $typeClass = $room['jenis_ruangan_raw'] === 'kelas'
                                                ? 'kelas'
                                                : (str_contains($room['jenis_ruangan_raw'], 'lab') ? 'lab' : 'other');
                                        @endphp
                                        <tr>
                                            <td data-label="No">{{ $number }}</td>
                                            <td data-label="Nama Ruangan">
                                                <div class="room-name">{{ $room['nama_ruangan'] }}</div>
                                                <div class="room-meta">{{ $room['lokasi'] !== '-' ? $room['lokasi'] : 'Lokasi belum diisi' }}</div>
                                            </td>
                                            <td data-label="Kode">{{ $room['kode_ruangan'] }}</td>
                                            <td data-label="Jenis"><span class="pill {{ $typeClass }}">{{ $room['jenis_ruangan'] }}</span></td>
                                            <td data-label="Kelas">{{ $room['unit'] }}</td>
                                            <td data-label="Wali / PJ">{{ $room['wali_kelas'] }}</td>
                                            <td data-label="Ketua Kelas">{{ $room['ketua_kelas'] }}</td>
                                            <td data-label="Total Inventaris">
                                                <div class="inventory-summary">
                                                    <strong>{{ number_format($room['total_inventaris']) }} item</strong>
                                                    <span class="room-meta">{{ number_format($room['barang_baik']) }} baik, {{ number_format($room['barang_rusak']) }} rusak</span>
                                                </div>
                                            </td>
                                            <td data-label="Kondisi">
                                                <span class="pill {{ $room['kondisi_class'] }}">{{ $room['kondisi_label'] }}</span>
                                                <div class="condition-copy">{{ $room['kondisi_ringkas'] }}</div>
                                            </td>
                                            <td data-label="Aksi">
                                                <div class="action-group">
                                                    <button type="button" class="row-action js-open-modal" data-modal="edit-room-{{ $room['id_ruangan'] }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                        <span>Edit</span>
                                                    </button>
                                                    <form method="POST" action="{{ route('superadmin.rooms.delete', $room['id_ruangan']) }}" onsubmit="return confirm('Hapus ruangan ini?');">
                                                        @csrf
                                                        <input type="hidden" name="q" value="{{ $filters['q'] }}">
                                                        <input type="hidden" name="type_filter" value="{{ $filters['type'] }}">
                                                        <input type="hidden" name="unit_filter" value="{{ $filters['unit'] }}">
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

                        @if ($rooms->hasPages())
                            <div class="pagination-wrap">
                                <div class="pagination">
                                    <span class="pagination-info">Halaman {{ $rooms->currentPage() }} dari {{ $rooms->lastPage() }}</span>

                                    @if ($rooms->onFirstPage())
                                        <span class="pagination-link disabled"><i class="bi bi-chevron-left"></i></span>
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

    <div class="modal-shell" id="modal-create-room" aria-hidden="true">
        <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateRoomTitle">
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="modalCreateRoomTitle">Tambah Ruangan</div>
                    <div class="modal-subtitle">Tambahkan identitas ruangan baru beserta unit, jenis, lokasi, dan status penggunaannya.</div>
                </div>
                <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('superadmin.rooms.store') }}" class="modal-form">
                @csrf
                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                <input type="hidden" name="type_filter" value="{{ $filters['type'] }}">
                <input type="hidden" name="unit_filter" value="{{ $filters['unit'] }}">

                    <div class="field-grid">
                        <div class="field-group">
                            <label for="createRoomName">Nama Ruangan</label>
                            <input id="createRoomName" type="text" name="nama_ruangan" value="{{ session('modal') === 'create-room' ? old('nama_ruangan') : '' }}" required>
                        </div>
                        <div class="field-group">
                            <label for="createRoomCode">Kode Ruangan</label>
                            <input id="createRoomCode" type="text" name="kode_ruangan_preview" value="{{ session('modal') === 'create-room' ? old('kode_ruangan_preview', old('nama_ruangan')) : '' }}" placeholder="Otomatis dari nama ruangan" readonly>
                        </div>
                        <div class="field-group">
                            <label for="createRoomType">Jenis Ruangan</label>
                            <select id="createRoomType" name="jenis_ruangan" required>
                                <option value="" disabled @selected(session('modal') !== 'create-room' || old('jenis_ruangan') === null || old('jenis_ruangan') === '')>Pilih jenis ruangan</option>
                                <option value="kelas" @selected(session('modal') === 'create-room' && old('jenis_ruangan') === 'kelas')>Kelas</option>
                                <option value="lab" @selected(session('modal') === 'create-room' && old('jenis_ruangan') === 'lab')>Lab</option>
                                <option value="kantor_guru" @selected(session('modal') === 'create-room' && old('jenis_ruangan') === 'kantor_guru')>Kantor Guru</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="createRoomUnit">Kelas</label>
                            @php
                                $selectedCreateUnit = session('modal') === 'create-room' ? old('unit') : '';
                            @endphp
                            <select id="createRoomUnit" name="unit" required>
                                <option value="" disabled @selected($selectedCreateUnit === null || $selectedCreateUnit === '')>Pilih kelas</option>
                                <option value="SMP" @selected($selectedCreateUnit === 'SMP')>SMP</option>
                                <option value="SMK" @selected($selectedCreateUnit === 'SMK')>SMK</option>
                                <option value="KANTOR" @selected($selectedCreateUnit === 'KANTOR')>Kantor</option>
                                <option value="LAB" @selected($selectedCreateUnit === 'LAB')>Lab</option>
                                <option value="UMUM" @selected($selectedCreateUnit === 'UMUM')>Umum</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="createRoomLocation">Lantai</label>
                            <select id="createRoomLocation" name="lokasi" required>
                                <option value="" disabled @selected(session('modal') !== 'create-room' || old('lokasi') === null || old('lokasi') === '')>Pilih lantai</option>
                                <option value="Lantai 1" @selected(session('modal') === 'create-room' && old('lokasi') === 'Lantai 1')>Lantai 1</option>
                                <option value="Lantai 2" @selected(session('modal') === 'create-room' && old('lokasi') === 'Lantai 2')>Lantai 2</option>
                                <option value="Lantai 3" @selected(session('modal') === 'create-room' && old('lokasi') === 'Lantai 3')>Lantai 3</option>
                                <option value="Lantai 4" @selected(session('modal') === 'create-room' && old('lokasi') === 'Lantai 4')>Lantai 4</option>
                            </select>
                        </div>
                    </div>

                <div class="modal-actions">
                    <button type="button" class="ghost-btn js-close-modal">Batal</button>
                    <button type="submit" class="submit-btn">
                        <i class="bi bi-check2-circle"></i>
                        <span>Simpan Ruangan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($roomRows as $room)
        @php
            $editModalId = 'edit-room-'.$room['id_ruangan'];
            $isEditModalOpen = session('modal') === $editModalId;
        @endphp

        <div class="modal-shell" id="modal-{{ $editModalId }}" aria-hidden="true">
            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditRoomTitle-{{ $room['id_ruangan'] }}">
                <div class="modal-header">
                    <div>
                        <div class="modal-title" id="modalEditRoomTitle-{{ $room['id_ruangan'] }}">Edit Ruangan</div>
                        <div class="modal-subtitle">Perbarui identitas dan informasi penggunaan untuk {{ $room['nama_ruangan'] }}.</div>
                    </div>
                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('superadmin.rooms.update', $room['id_ruangan']) }}" class="modal-form">
                    @csrf
                    <input type="hidden" name="q" value="{{ $filters['q'] }}">
                    <input type="hidden" name="type_filter" value="{{ $filters['type'] }}">
                    <input type="hidden" name="unit_filter" value="{{ $filters['unit'] }}">

                    <div class="field-grid">
                        <div class="field-group">
                            <label for="editRoomName{{ $room['id_ruangan'] }}">Nama Ruangan</label>
                            <input id="editRoomName{{ $room['id_ruangan'] }}" type="text" name="nama_ruangan" value="{{ $isEditModalOpen ? old('nama_ruangan', $room['nama_ruangan']) : $room['nama_ruangan'] }}" required>
                        </div>
                        <div class="field-group">
                            <label for="editRoomCode{{ $room['id_ruangan'] }}">Kode Ruangan</label>
                            <input id="editRoomCode{{ $room['id_ruangan'] }}" type="text" name="kode_ruangan_preview" value="{{ $isEditModalOpen ? old('kode_ruangan_preview', $room['kode_ruangan']) : $room['kode_ruangan'] }}" placeholder="Otomatis dari nama ruangan" readonly>
                        </div>
                        <div class="field-group">
                            <label for="editRoomType{{ $room['id_ruangan'] }}">Jenis Ruangan</label>
                            @php
                                $selectedRoomType = $isEditModalOpen ? old('jenis_ruangan', $room['jenis_ruangan_raw']) : $room['jenis_ruangan_raw'];
                            @endphp
                            <select id="editRoomType{{ $room['id_ruangan'] }}" name="jenis_ruangan" required>
                                <option value="kelas" @selected($selectedRoomType === 'kelas')>Kelas</option>
                                <option value="lab" @selected($selectedRoomType === 'lab')>Lab</option>
                                <option value="kantor_guru" @selected($selectedRoomType === 'kantor_guru')>Kantor Guru</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="editRoomUnit{{ $room['id_ruangan'] }}">Kelas</label>
                            @php
                                $selectedUnit = strtoupper((string) ($isEditModalOpen ? old('unit', $room['unit']) : $room['unit']));
                            @endphp
                            <select id="editRoomUnit{{ $room['id_ruangan'] }}" name="unit" required>
                                <option value="SMP" @selected($selectedUnit === 'SMP')>SMP</option>
                                <option value="SMK" @selected($selectedUnit === 'SMK')>SMK</option>
                                <option value="KANTOR" @selected($selectedUnit === 'KANTOR')>Kantor</option>
                                <option value="LAB" @selected($selectedUnit === 'LAB')>Lab</option>
                                <option value="UMUM" @selected($selectedUnit === 'UMUM')>Umum</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="editRoomLocation{{ $room['id_ruangan'] }}">Lantai</label>
                            @php
                                $selectedFloor = $isEditModalOpen ? old('lokasi', $room['lokasi'] === '-' ? '' : $room['lokasi']) : ($room['lokasi'] === '-' ? '' : $room['lokasi']);
                            @endphp
                            <select id="editRoomLocation{{ $room['id_ruangan'] }}" name="lokasi" required>
                                <option value="Lantai 1" @selected($selectedFloor === 'Lantai 1')>Lantai 1</option>
                                <option value="Lantai 2" @selected($selectedFloor === 'Lantai 2')>Lantai 2</option>
                                <option value="Lantai 3" @selected($selectedFloor === 'Lantai 3')>Lantai 3</option>
                                <option value="Lantai 4" @selected($selectedFloor === 'Lantai 4')>Lantai 4</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                        <button type="submit" class="submit-btn">
                            <i class="bi bi-save2-fill"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        (function () {
            const page = document.body;
            const modalTriggers = document.querySelectorAll('.js-open-modal');
            const closeButtons = document.querySelectorAll('.js-close-modal');
            const modalShells = document.querySelectorAll('.modal-shell');
            const codeRules = [
                { nameId: 'createRoomName', codeId: 'createRoomCode' },
                @foreach ($roomRows as $room)
                { nameId: 'editRoomName{{ $room['id_ruangan'] }}', codeId: 'editRoomCode{{ $room['id_ruangan'] }}' },
                @endforeach
            ];

            function generateRoomCode(value) {
                return (value || '')
                    .toUpperCase()
                    .trim()
                    .replace(/[^A-Z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }

            function openModal(modalId) {
                const modal = document.getElementById('modal-' + modalId);

                if (!modal) {
                    return;
                }

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                window.InfraSPHScrollLock?.lock();
                page.style.overflow = 'hidden';
            }

            function closeModal(modal) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');

                if (!document.querySelector('.modal-shell.is-open')) {
                    window.InfraSPHScrollLock?.unlock();
                    page.style.overflow = '';
                }
            }

            modalTriggers.forEach((trigger) => {
                trigger.addEventListener('click', () => openModal(trigger.dataset.modal));
            });

            codeRules.forEach((rule) => {
                const nameInput = document.getElementById(rule.nameId);
                const codeInput = document.getElementById(rule.codeId);

                if (!nameInput || !codeInput) {
                    return;
                }

                const syncCode = () => {
                    codeInput.value = generateRoomCode(nameInput.value);
                };

                syncCode();
                nameInput.addEventListener('input', syncCode);
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.modal-shell');

                    if (modal) {
                        closeModal(modal);
                    }
                });
            });

            modalShells.forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key !== 'Escape') {
                    return;
                }

                const openedModal = document.querySelector('.modal-shell.is-open');

                if (openedModal) {
                    closeModal(openedModal);
                }
            });

            const autoOpenModal = @json(session('modal'));

            if (autoOpenModal) {
                openModal(autoOpenModal);
            }
        })();
    </script>
</body>
</html>

