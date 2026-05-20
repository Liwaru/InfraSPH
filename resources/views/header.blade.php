<link rel="stylesheet" href="{{ asset('css/views/header.css') }}">

<header class="mobile-appbar">
    <a class="mobile-appbar-brand" href="{{ route('dashboard') }}">
        <span class="mobile-appbar-logo"><img src="{{ asset('images/InfraSPH.png') }}" alt="Logo InfraSPH"></span>
        <span class="mobile-appbar-title">InfraSPH</span>
    </a>
    <button type="button" class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Buka navigasi" aria-expanded="false" aria-controls="sidebarNavigation">
        <i class="bi bi-list"></i>
    </button>
</header>
<div class="mobile-sidebar-backdrop" id="mobileSidebarBackdrop" aria-hidden="true"></div>

<aside class="sidebar" id="sidebarNavigation">
    <div class="sidebar-brand">
        <a class="sidebar-brand-link" href="{{ route('dashboard') }}">
            <span class="sidebar-brand-main">
                <span class="sidebar-brand-logo">
            <img src="{{ asset('images/InfraSPH.png') }}" alt="Logo InfraSPH">
                </span>
                <span class="sidebar-brand-text">InfraSPH</span>
            </span>
        </a>
        <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="true">
            <i class="bi bi-list"></i>
        </button>
    </div>

    @php
        $level = (int) ($user['level'] ?? 0);
        $safeRoute = static function (string $name): string {
            return \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';
        };
        $menuService = app(\App\Services\MenuAccessService::class);
        $menus = collect($menuService->sidebarMenusForLevel($level))
            ->map(function (array $menu) use ($safeRoute) {
                return [
                    'label' => $menu['label'],
                    'icon' => $menu['icon'],
                    'url' => $safeRoute($menu['route']),
                ];
            })
            ->values()
            ->all();

        if ($menus === []) {
            $menus = [
                ['label' => 'Dashboard', 'icon' => 'bi bi-grid-1x2-fill', 'url' => route('dashboard')],
            ];
        }
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

    <div class="sidebar-account" id="sidebarAccount">
        <button type="button" class="sidebar-user-info" id="accountMenuToggle" aria-label="Buka menu akun" aria-expanded="false" aria-controls="accountMenu">
            <span class="sidebar-avatar notranslate" translate="no">
                {{ strtoupper(substr($user['nama'] ?? 'U', 0, 1)) }}
            </span>
            <span class="sidebar-user-details">
                <span class="sidebar-user-name notranslate" translate="no">{{ $user['nama'] ?? 'Pengguna' }}</span>
                <span class="sidebar-user-role">{{ $dashboard['role_name'] ?? 'Role' }}</span>
            </span>
        </button>

        <div class="account-menu" id="accountMenu">
            <div class="account-menu-head">
                <div class="account-menu-avatar notranslate" translate="no">{{ strtoupper(substr($user['nama'] ?? 'U', 0, 1)) }}</div>
                <div class="sidebar-user-details">
                    <div class="account-menu-name notranslate" translate="no">{{ $user['nama'] ?? 'Pengguna' }}</div>
                    <div class="account-menu-role">{{ $dashboard['role_name'] ?? 'Role' }}</div>
                </div>
            </div>
            <div class="account-menu-main" id="accountMenuMain">
                <a class="account-menu-link" href="{{ route('profile.show') }}">
                    <i class="bi bi-person"></i>
                    <span>Profil</span>
                </a>
                <a class="account-menu-link" href="{{ route('security.show') }}">
                    <i class="bi bi-shield-lock"></i>
                    <span>Keamanan</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="account-menu-logout">
                    @csrf
                    <button type="submit" class="account-menu-btn danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<script>
    (function () {
        function lockUserDataFromMachineTranslation() {
            const selectors = [
                'input',
                'textarea',
                'select',
                '[data-label="Nama"]',
                '[data-label="Nama User"]',
                '[data-label="Peminta"]',
                '[data-label="Email"]',
                '[data-label="NIS"]',
                '[data-label="Kode"]',
                '[data-label="Kelas"]',
                '[data-label="Kelas / Ruangan"]',
                '[data-label="Ruangan"]',
                '[data-label="Barang"]',
                '.cell-primary',
                '.cell-secondary',
                '.cell-strong',
                '.primary-text',
                '.muted-text',
                '.room-name',
                '.room-code',
                '.room-title',
                '.room-meta',
                '.room-type',
                '.request-code',
                '.request-item',
                '.assignment-name',
                '.assignment-room',
                '.user-name',
                '.user-meta',
                '.readonly-value',
                '.info-value',
                '.detail-value',
                '.preview-value'
            ];

            document.querySelectorAll(selectors.join(',')).forEach(function (element) {
                element.classList.add('notranslate');
                element.setAttribute('translate', 'no');
            });
        }

        function initSidebarToggle() {
            const appShell = document.getElementById('appShell');
            const toggleButton = document.getElementById('sidebarToggle');
            const mobileToggleButton = document.getElementById('mobileMenuToggle');
            const mobileBackdrop = document.getElementById('mobileSidebarBackdrop');
            const account = document.getElementById('sidebarAccount');
            const accountToggle = document.getElementById('accountMenuToggle');

            function closeAccountMenu() {
                if (!account || !accountToggle) {
                    return;
                }

                account.classList.remove('open');
                accountToggle.setAttribute('aria-expanded', 'false');
            }

            if (account && accountToggle) {
                accountToggle.addEventListener('click', function () {
                    const isOpen = account.classList.toggle('open');
                    accountToggle.setAttribute('aria-expanded', String(isOpen));
                });

                document.addEventListener('click', function (event) {
                    if (!account.contains(event.target)) {
                        closeAccountMenu();
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeAccountMenu();
                    }
                });
            }

            function closeMobileSidebar() {
                if (!appShell || !mobileToggleButton) { return; }
                appShell.classList.remove('sidebar-mobile-open');
                mobileToggleButton.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
                closeAccountMenu();
            }
            function openMobileSidebar() {
                if (!appShell || !mobileToggleButton) { return; }
                appShell.classList.add('sidebar-mobile-open');
                mobileToggleButton.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }
            mobileToggleButton?.addEventListener('click', function () {
                if (appShell?.classList.contains('sidebar-mobile-open')) { closeMobileSidebar(); } else { openMobileSidebar(); }
            });
            mobileBackdrop?.addEventListener('click', closeMobileSidebar);
            document.addEventListener('keydown', function (event) { if (event.key === 'Escape') { closeMobileSidebar(); } });
            window.addEventListener('resize', function () { if (window.innerWidth > 860) { closeMobileSidebar(); } });

            if (!appShell || !toggleButton || window.innerWidth <= 860) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                appShell.classList.toggle('sidebar-collapsed');
                const expanded = !appShell.classList.contains('sidebar-collapsed');
                toggleButton.setAttribute('aria-expanded', String(expanded));
                closeAccountMenu();
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                lockUserDataFromMachineTranslation();
                initSidebarToggle();
            }, { once: true });
        } else {
            lockUserDataFromMachineTranslation();
            initSidebarToggle();
        }
    })();
</script>

