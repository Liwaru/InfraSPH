<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data User | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/data_users.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="superadmin-users-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div>
                        <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengelola Sistem' }}</div>
                        <h1 class="hero-title">Data User</h1>
                        <p class="hero-subtitle">Kelola akun, lihat role user, dan pantau keterhubungan user dengan ruangan melalui penugasan aktif maupun histori penugasan dalam satu halaman.</p>
                    </div>
                </section>

                <section class="summary-grid">
                    <article class="summary-card">
                        <div class="summary-label">Total User</div>
                        <div class="summary-value">{{ number_format($summary['total_user']) }}</div>
                        <div class="summary-note">Semua akun yang terdaftar di sistem</div>
                    </article>
                    <article class="summary-card is-accent">
                        <div class="summary-label">Ketua Kelas</div>
                        <div class="summary-value">{{ number_format($summary['ketua_kelas']) }}</div>
                        <div class="summary-note">User dengan role ketua kelas</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Wali Kelas</div>
                        <div class="summary-value">{{ number_format($summary['wali_kelas']) }}</div>
                        <div class="summary-note">Akun yang bertugas verifikasi kelas</div>
                    </article>
                    <article class="summary-card">
                        <div class="summary-label">Penugasan Aktif</div>
                        <div class="summary-value">{{ number_format($summary['penugasan_aktif']) }}</div>
                        <div class="summary-note">Koneksi user ke ruangan yang masih aktif</div>
                    </article>
                </section>

                <section class="filter-card">
                    <form method="GET" action="{{ route('superadmin.users') }}" class="filter-form">
                        <div class="filter-field">
                            <label for="searchUser">Search Nama / NIS</label>
                            <input id="searchUser" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama, email, atau NIS">
                        </div>
                        <div class="filter-field">
                            <label for="roleFilter">Filter Role</label>
                            <select id="roleFilter" name="role">
                                <option value="semua" @selected($filters['role'] === 'semua')>Semua Role</option>
                                @foreach ($roleOptions as $level => $label)
                                    <option value="{{ $level }}" @selected($filters['role'] === (string) $level)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label for="roomTypeFilter">Jenis Ruangan</label>
                            <select id="roomTypeFilter" name="room_type">
                                <option value="semua" @selected($filters['room_type'] === 'semua')>Semua Jenis</option>
                                @foreach ($roomTypeOptions as $type)
                                    <option value="{{ $type }}" @selected($filters['room_type'] === $type)>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="filter-btn">
                                <i class="bi bi-funnel-fill"></i>
                                <span>Terapkan</span>
                            </button>
                            <a href="{{ route('superadmin.users') }}" class="filter-link">
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
                            <div class="table-title">Manajemen User</div>
                            <div class="table-subtitle">Menampilkan {{ number_format($users->total()) }} user berdasarkan filter aktif.</div>
                        </div>
                        <div class="table-header-actions">
                            <button type="button" class="action-btn primary js-open-modal" data-modal="create-user">
                                <i class="bi bi-person-plus-fill"></i>
                                <span>Tambah User</span>
                            </button>
                        </div>
                    </div>

                    @if (count($userRows) === 0)
                        <div class="empty-state">Belum ada data user yang cocok dengan filter saat ini.</div>
                    @else
                        <div class="table-wrap">
                            <table class="mobile-card-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>NIS</th>
                                        <th>Role</th>
                                        <th>Ruangan/Kelas</th>
                                        <th>Peran Ruangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userRows as $row)
                                        <tr>
                                            <td data-label="Nama">
                                                <div class="user-name">{{ $row['nama'] }}</div>
                                                <div class="user-meta">
                                                    ID User: {{ $row['id_user'] }}
                                                </div>
                                            </td>
                                            <td data-label="Email">{{ $row['email_label'] }}</td>
                                            <td data-label="NIS">{{ $row['nis'] }}</td>
                                            <td data-label="Role">
                                                <span class="pill {{ $row['role_class'] }}">{{ $row['role_label'] }}</span>
                                            </td>
                                            <td data-label="Ruangan/Kelas">
                                                @if ($row['assignment_count'] === 0)
                                                    <span class="muted-text">Belum ada penugasan</span>
                                                @else
                                                    <div class="assignment-stack">
                                                        @foreach (array_slice($row['assignments'], 0, 2) as $assignment)
                                                            <div class="assignment-item">
                                                                <div class="assignment-room">{{ $assignment['nama_ruangan'] }}</div>
                                                            </div>
                                                        @endforeach
                                                        @if ($row['assignment_count'] > 2)
                                                            <div class="user-meta">+{{ $row['assignment_count'] - 2 }} penugasan lain</div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td data-label="Peran Ruangan">
                                                @if ($row['assignment_count'] === 0)
                                                    <span class="muted-text">Belum ditentukan</span>
                                                @else
                                                    <div class="assignment-stack">
                                                        @foreach (array_slice($row['assignments'], 0, 2) as $assignment)
                                                            <div>{{ $assignment['peran_label'] }}</div>
                                                        @endforeach
                                                        @if ($row['assignment_count'] > 2)
                                                            <div class="user-meta">dan lainnya</div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td data-label="Aksi">
                                                <div class="action-group">
                                                    <button type="button" class="row-action js-open-modal" data-modal="edit-user-{{ $row['id_user'] }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                        <span>Edit</span>
                                                    </button>
                                                    <button type="button" class="row-action primary js-open-modal" data-modal="assignment-user-{{ $row['id_user'] }}">
                                                        <i class="bi bi-diagram-3-fill"></i>
                                                        <span>Atur Penugasan</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($users->hasPages())
                            <div class="pagination-wrap">
                                <div class="pagination">
                                    <span class="pagination-info">Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</span>

                                    @if ($users->onFirstPage())
                                        <span class="pagination-link disabled"><i class="bi bi-chevron-left"></i></span>
                                    @else
                                        <a href="{{ $users->previousPageUrl() }}" class="pagination-link" aria-label="Halaman sebelumnya">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    @endif

                                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                        @if ($page === $users->currentPage())
                                            <span class="pagination-current">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if ($users->hasMorePages())
                                        <a href="{{ $users->nextPageUrl() }}" class="pagination-link" aria-label="Halaman berikutnya">
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

    <div class="modal-shell" id="modal-create-user" aria-hidden="true">
        <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateUserTitle">
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="modalCreateUserTitle">Tambah User</div>
                    <div class="modal-subtitle">Buat akun baru untuk ketua kelas, wali kelas, pengelola sistem, atau kepala sekolah.</div>
                </div>
                <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('superadmin.users.store') }}" class="modal-form">
                @csrf
                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                <input type="hidden" name="role_filter" value="{{ $filters['role'] }}">
                <input type="hidden" name="room_type_filter" value="{{ $filters['room_type'] }}">

                <div class="field-grid">
                    <div class="field-group">
                        <label for="createName">Nama</label>
                        <input id="createName" type="text" name="nama" value="{{ session('modal') === 'create-user' ? old('nama') : '' }}" required>
                    </div>
                    <div class="field-group">
                        <label for="createNis">NIS</label>
                        <input id="createNis" type="text" name="nis" value="{{ session('modal') === 'create-user' ? old('nis') : '' }}" placeholder="Opsional">
                    </div>
                    <div class="field-group">
                        <label for="createEmail">Email</label>
                        <input id="createEmail" type="email" name="email" value="{{ session('modal') === 'create-user' ? old('email') : '' }}" required>
                    </div>
                    <div class="field-group">
                        <label for="createRole">Role</label>
                        <select id="createRole" name="level" required>
                            <option value="">Pilih role</option>
                            @foreach ($roleOptions as $level => $label)
                                <option value="{{ $level }}" @selected(session('modal') === 'create-user' && old('level') === (string) $level)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="createPassword">Password</label>
                        <input id="createPassword" type="password" name="password" required>
                    </div>
                </div>

                <div class="help-box">Saran struktur: menu ini fokus ke akun, sedangkan hubungan user ke ruangan diatur lewat form penugasan. Jadi identitas user dan penugasan tetap terpisah, tapi masih nyaman dikelola dari satu halaman.</div>

                <div class="modal-actions">
                    <button type="button" class="ghost-btn js-close-modal">Batal</button>
                    <button type="submit" class="submit-btn">
                        <i class="bi bi-check2-circle"></i>
                        <span>Simpan User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($userRows as $row)
        @php
            $editModalId = 'edit-user-'.$row['id_user'];
            $assignmentModalId = 'assignment-user-'.$row['id_user'];
            $primaryAssignment = $row['primary_assignment'];
            $isEditModalOpen = session('modal') === $editModalId;
            $isAssignmentModalOpen = session('modal') === $assignmentModalId;
        @endphp

        <div class="modal-shell" id="modal-{{ $editModalId }}" aria-hidden="true">
            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditUserTitle-{{ $row['id_user'] }}">
                <div class="modal-header">
                    <div>
                        <div class="modal-title" id="modalEditUserTitle-{{ $row['id_user'] }}">Edit User</div>
                        <div class="modal-subtitle">Perbarui identitas dan role untuk {{ $row['nama'] }}.</div>
                    </div>
                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('superadmin.users.update', $row['id_user']) }}" class="modal-form">
                    @csrf
                    <input type="hidden" name="q" value="{{ $filters['q'] }}">
                    <input type="hidden" name="role_filter" value="{{ $filters['role'] }}">
                    <input type="hidden" name="room_type_filter" value="{{ $filters['room_type'] }}">

                    <div class="field-grid">
                        <div class="field-group">
                            <label for="editName{{ $row['id_user'] }}">Nama</label>
                            <input id="editName{{ $row['id_user'] }}" type="text" name="nama" value="{{ $isEditModalOpen ? old('nama', $row['nama']) : $row['nama'] }}" required>
                        </div>
                        <div class="field-group">
                            <label for="editNis{{ $row['id_user'] }}">NIS</label>
                            <input id="editNis{{ $row['id_user'] }}" type="text" name="nis" value="{{ $isEditModalOpen ? old('nis', $row['nis_raw']) : $row['nis_raw'] }}" placeholder="Opsional">
                        </div>
                        <div class="field-group">
                            <label for="editEmail{{ $row['id_user'] }}">Email</label>
                            <input id="editEmail{{ $row['id_user'] }}" type="email" name="email" value="{{ $isEditModalOpen ? old('email', $row['email']) : $row['email'] }}" required>
                        </div>
                        <div class="field-group">
                            <label for="editRole{{ $row['id_user'] }}">Role</label>
                            <select id="editRole{{ $row['id_user'] }}" name="level" required>
                                @foreach ($roleOptions as $level => $label)
                                    <option value="{{ $level }}" @selected((int) ($isEditModalOpen ? old('level', $row['level']) : $row['level']) === (int) $level)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="editPassword{{ $row['id_user'] }}">Password Baru</label>
                            <input id="editPassword{{ $row['id_user'] }}" type="password" name="password" placeholder="Kosongkan jika tidak diubah">
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

        <div class="modal-shell" id="modal-{{ $assignmentModalId }}" aria-hidden="true">
            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalAssignmentTitle-{{ $row['id_user'] }}">
                <div class="modal-header">
                    <div>
                        <div class="modal-title" id="modalAssignmentTitle-{{ $row['id_user'] }}">Atur Penugasan</div>
                        <div class="modal-subtitle">Kelola hubungan {{ $row['nama'] }} dengan ruangan dan tentukan peran serta status penugasannya.</div>
                    </div>
                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('superadmin.users.assignment', $row['id_user']) }}" class="modal-form">
                    @csrf
                    <input type="hidden" name="q" value="{{ $filters['q'] }}">
                    <input type="hidden" name="role_filter" value="{{ $filters['role'] }}">
                    <input type="hidden" name="room_type_filter" value="{{ $filters['room_type'] }}">
                    <input type="hidden" name="assignment_id" value="{{ $isAssignmentModalOpen ? old('assignment_id', $primaryAssignment['id_penugasan_ruangan'] ?? '') : ($primaryAssignment['id_penugasan_ruangan'] ?? '') }}">

                    @if ($row['assignment_count'] > 0)
                        <div class="help-box">
                            Penugasan saat ini:
                            @foreach ($row['assignments'] as $assignment)
                                <div>{{ $assignment['nama_ruangan'] }} • {{ $assignment['peran_label'] }} • {{ $assignment['status_label'] }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="field-grid">
                        <div class="field-group">
                            <label for="assignmentRoom{{ $row['id_user'] }}">Ruangan / Kelas</label>
                            <select id="assignmentRoom{{ $row['id_user'] }}" name="id_ruangan" required>
                                <option value="">Pilih ruangan</option>
                                @foreach ($availableRooms as $room)
                                    @php
                                        $selectedRoom = $isAssignmentModalOpen
                                            ? old('id_ruangan', $primaryAssignment['id_ruangan'] ?? '')
                                            : ($primaryAssignment['id_ruangan'] ?? '');
                                    @endphp
                                    <option value="{{ $room->id_ruangan }}" @selected((string) $selectedRoom === (string) $room->id_ruangan)>
                                        {{ $room->nama_ruangan }} ({{ $room->kode_ruangan }} - {{ ucfirst($room->jenis_ruangan) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="assignmentRole{{ $row['id_user'] }}">Peran Ruangan</label>
                            <input id="assignmentRole{{ $row['id_user'] }}" type="text" name="peran_ruangan" value="{{ $isAssignmentModalOpen ? old('peran_ruangan', $primaryAssignment['peran_ruangan'] ?? '') : ($primaryAssignment['peran_ruangan'] ?? '') }}" placeholder="Contoh: wali_kelas atau ketua_kelas" required>
                        </div>
                        <div class="field-group">
                            <label for="assignmentStatus{{ $row['id_user'] }}">Status Penugasan</label>
                            <select id="assignmentStatus{{ $row['id_user'] }}" name="status" required>
                                @php
                                    $selectedAssignmentStatus = $isAssignmentModalOpen
                                        ? old('status', $primaryAssignment['status'] ?? 'aktif')
                                        : ($primaryAssignment['status'] ?? 'aktif');
                                @endphp
                                <option value="aktif" @selected($selectedAssignmentStatus === 'aktif')>Aktif</option>
                                <option value="nonaktif" @selected($selectedAssignmentStatus === 'nonaktif')>Nonaktif</option>
                            </select>
                        </div>
                        <div class="field-group">
                            <label for="assignmentStart{{ $row['id_user'] }}">Tanggal Mulai</label>
                            <input id="assignmentStart{{ $row['id_user'] }}" type="date" name="tanggal_mulai" value="{{ $isAssignmentModalOpen ? old('tanggal_mulai', $primaryAssignment['tanggal_mulai'] ?? '') : ($primaryAssignment['tanggal_mulai'] ?? '') }}">
                        </div>
                        <div class="field-group">
                            <label for="assignmentEnd{{ $row['id_user'] }}">Tanggal Selesai</label>
                            <input id="assignmentEnd{{ $row['id_user'] }}" type="date" name="tanggal_selesai" value="{{ $isAssignmentModalOpen ? old('tanggal_selesai', $primaryAssignment['tanggal_selesai'] ?? '') : ($primaryAssignment['tanggal_selesai'] ?? '') }}">
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                        <button type="submit" class="submit-btn">
                            <i class="bi bi-diagram-3-fill"></i>
                            <span>Simpan Penugasan</span>
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

            function openModal(modalId) {
                const modal = document.getElementById('modal-' + modalId);

                if (!modal) {
                    return;
                }

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                page.style.overflow = 'hidden';
            }

            function closeModal(modal) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');

                if (!document.querySelector('.modal-shell.is-open')) {
                    page.style.overflow = '';
                }
            }

            modalTriggers.forEach((trigger) => {
                trigger.addEventListener('click', () => openModal(trigger.dataset.modal));
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

