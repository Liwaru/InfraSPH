<aside class="sidebar">
    <div class="sidebar-brand">
        <a class="sidebar-brand-link" href="{{ route('dashboard') }}">
            <span class="sidebar-brand-main">
                <span class="sidebar-brand-logo">
                    <img src="{{ asset('images/Infrasph.png') }}" alt="Logo InfraSPH">
                </span>
                <span class="sidebar-brand-text">InfraSPH</span>
            </span>
        </a>
        <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="true">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="sidebar-user-info">
        <div class="sidebar-avatar">
            {{ strtoupper(substr($user['nama'] ?? 'U', 0, 1)) }}
        </div>
        <div class="sidebar-user-details">
            <div class="sidebar-user-name">{{ $user['nama'] ?? 'Pengguna' }}</div>
            <div class="sidebar-user-role">{{ $dashboard['role_name'] ?? 'Role' }}</div>
        </div>
    </div>

    @php
        $level = (int) ($user['level'] ?? 0);
        $safeRoute = static function (string $name): string {
            return \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';
        };
        $menuByLevel = [
            1 => [
                ['label' => 'Kelas Saya', 'icon' => 'bi bi-door-open-fill', 'url' => $safeRoute('class.room')],
                ['label' => 'Ajukan Permintaan', 'icon' => 'bi bi-send-plus-fill', 'url' => $safeRoute('requests.create')],
                ['label' => 'Riwayat Pengajuan', 'icon' => 'bi bi-clock-history', 'url' => $safeRoute('requests.history')],
            ],
            2 => [
                ['label' => 'Kelas Binaan', 'icon' => 'bi bi-building', 'url' => $safeRoute('admin.classroom')],
                ['label' => 'Pengajuan Masuk', 'icon' => 'bi bi-inbox-fill', 'url' => $safeRoute('admin.requests.inbox')],
                ['label' => 'Riwayat Verifikasi', 'icon' => 'bi bi-patch-check-fill', 'url' => $safeRoute('admin.requests.history')],
            ],
            3 => [
                ['label' => 'Data User', 'icon' => 'bi bi-people-fill', 'url' => $safeRoute('superadmin.users')],
                ['label' => 'Data Ruangan', 'icon' => 'bi bi-building-fill-gear', 'url' => $safeRoute('superadmin.rooms')],
                ['label' => 'Data Barang', 'icon' => 'bi bi-grid-fill', 'url' => $safeRoute('superadmin.items')],
                ['label' => 'Data Inventaris', 'icon' => 'bi bi-box-seam-fill', 'url' => $safeRoute('superadmin.inventories')],
                ['label' => 'Realisasi Pengajuan', 'icon' => 'bi bi-list-check', 'url' => $safeRoute('superadmin.requests.realization')],
                ['label' => 'Asisten Sistem', 'icon' => 'bi bi-robot', 'url' => $safeRoute('assistant.index')],
                ['label' => 'Laporan', 'icon' => 'bi bi-bar-chart-line-fill', 'url' => $safeRoute('superadmin.reports')],
            ],
            4 => [
                ['label' => 'Semua Ruangan', 'icon' => 'bi bi-buildings-fill', 'url' => $safeRoute('owner.rooms')],
                ['label' => 'Inventaris Sekolah', 'icon' => 'bi bi-boxes', 'url' => $safeRoute('owner.inventories')],
                ['label' => 'Persetujuan Akhir', 'icon' => 'bi bi-clipboard2-check-fill', 'url' => $safeRoute('owner.requests.approval')],
                ['label' => 'Asisten Sistem', 'icon' => 'bi bi-robot', 'url' => $safeRoute('assistant.index')],
                ['label' => 'Laporan', 'icon' => 'bi bi-bar-chart-fill', 'url' => $safeRoute('owner.reports')],
            ],
        ];
        $menus = $menuByLevel[$level] ?? [
            ['label' => 'Dashboard', 'icon' => 'bi bi-grid-1x2-fill', 'url' => route('dashboard')],
        ];
    @endphp

    <ul class="sidebar-nav">
        @foreach ($menus as $menu)
            <li>
                <a href="{{ $menu['url'] }}" @class(['active' => request()->url() === $menu['url']])>
                    <i class="{{ $menu['icon'] }}"></i>
                    <span>{{ $menu['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <form action="{{ route('logout') }}" method="POST" class="sidebar-logout">
        @csrf
        <button type="submit" class="sidebar-logout-btn">
            <i class="bi bi-box-arrow-right"></i>
            <span>Log Out</span>
        </button>
    </form>
</aside>
