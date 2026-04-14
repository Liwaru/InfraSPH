<aside class="sidebar">
    <div class="sidebar-brand">
        <a class="sidebar-brand-link" href="{{ route('home') }}">
            <span class="sidebar-brand-main">
                <span class="sidebar-brand-logo">
                    <img src="{{ asset('images/Infrasph oren.png') }}" alt="Logo InfraSPH">
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
        $menuByLevel = [
            1 => [
                ['label' => 'kelas Saya', 'icon' => 'bi bi-door-open-fill', 'url' => '#'],
                ['label' => 'Inventaris Saya', 'icon' => 'bi bi-box-seam-fill', 'url' => '#'],
                ['label' => 'Ajukan Permintaan', 'icon' => 'bi bi-send-plus-fill', 'url' => '#'],
                ['label' => 'Riwayat Pengajuan', 'icon' => 'bi bi-clock-history', 'url' => '#'],
                ['label' => 'Customer Service', 'icon' => 'bi bi-headset', 'url' => '#'],

            ],
            2 => [
                ['label' => 'Ruangan Tugas', 'icon' => 'bi bi-building', 'url' => '#'],
                ['label' => 'Inventaris Kelas', 'icon' => 'bi bi-box-seam-fill', 'url' => '#'],
                ['label' => 'Pengajuan Masuk', 'icon' => 'bi bi-inbox-fill', 'url' => '#'],
                ['label' => 'Customer Service', 'icon' => 'bi bi-headset', 'url' => '#'],
                ['label' => 'Riwayat Verifikasi', 'icon' => 'bi bi-patch-check-fill', 'url' => '#'],
            ],
            3 => [
                ['label' => 'Semua Ruangan', 'icon' => 'bi bi-buildings-fill', 'url' => '#'],
                ['label' => 'Inventaris Sekolah', 'icon' => 'bi bi-boxes', 'url' => '#'],
                ['label' => 'Persetujuan Akhir', 'icon' => 'bi bi-clipboard2-check-fill', 'url' => '#'],
                ['label' => 'Asisten Sistem', 'icon' => 'bi bi-robot', 'url' => '#'],
                ['label' => 'Laporan', 'icon' => 'bi bi-bar-chart-fill', 'url' => '#'],
            ],
            4 => [
                ['label' => 'Data User', 'icon' => 'bi bi-people-fill', 'url' => '#'],
                ['label' => 'Data Ruangan', 'icon' => 'bi bi-building-fill-gear', 'url' => '#'],
                ['label' => 'Data Inventaris', 'icon' => 'bi bi-box-seam-fill', 'url' => '#'],
                ['label' => 'Realisasi Pengajuan', 'icon' => 'bi bi-list-check', 'url' => '#'],
                ['label' => 'Asisten Sistem', 'icon' => 'bi bi-robot', 'url' => '#'],
                ['label' => 'Laporan', 'icon' => 'bi bi-bar-chart-line-fill', 'url' => '#'],
            ],
        ];
        $menus = $menuByLevel[$level] ?? [
            ['label' => 'Dashboard', 'icon' => 'bi bi-grid-1x2-fill', 'url' => route('home')],
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
