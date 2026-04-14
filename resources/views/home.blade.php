<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | InfraSPH</title>
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
            --surface: #ffffff;
            --border-soft: #f2e7df;
            --page-bg: #fff8f4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .app-shell {
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 345px;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, var(--sidebar-top), var(--sidebar-bottom));
            color: var(--sidebar-text);
            padding: 1.25rem 1rem 1rem;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 14px 0 34px rgba(225, 79, 0, 0.18);
            transition: transform 0.28s ease, width 0.28s ease, box-shadow 0.28s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.7rem 0.55rem 1rem;
            margin-bottom: 0.85rem;
            border-bottom: 1px solid var(--sidebar-border);
            min-width: 0;
            justify-content: space-between;
        }

        .sidebar-brand-link {
            min-width: 0;
            flex: 1 1 auto;
            text-decoration: none;
        }

        .sidebar-brand-logo {
            width: 104px;
            height: 86px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 16px 28px -20px rgba(255, 89, 0, 0.28);
            flex-shrink: 0;
        }

        .sidebar-brand img {
            width: 180px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 10px 18px rgba(255, 255, 255, 0.16));
        }

        .sidebar-brand-text {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #ffffff;
            min-width: 0;
            flex: 1 1 auto;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-brand-main {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            min-width: 0;
            flex: 1 1 auto;
        }

        .sidebar-toggle {
            margin-left: auto;
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.14);
            color: #ffffff;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
            flex-shrink: 0;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-1px);
        }

        .sidebar-toggle i {
            font-size: 1.5rem;
            line-height: 1;
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin: 0.2rem 0 1rem;
            padding: 0.9rem 0.85rem;
            background: rgba(255, 255, 255, 0.13);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 18px;
        }

        .sidebar-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.24);
            color: #ffffff;
            font-weight: 800;
            border: 2px solid rgba(255, 255, 255, 0.35);
            flex-shrink: 0;
        }

        .sidebar-user-details {
            min-width: 0;
            flex: 1 1 auto;
        }

        .sidebar-user-name {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.78rem;
            margin-top: 0.15rem;
        }

        .sidebar-nav {
            list-style: none;
            display: grid;
            gap: 0.35rem;
            margin: 0;
            padding: 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.9rem 0.95rem;
            border-radius: 16px;
            color: rgba(255, 255, 255, 0.94);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            transition: background 0.2s ease, transform 0.2s ease, color 0.2s ease;
        }

        .sidebar-nav a:hover {
            background: var(--sidebar-hover);
            transform: translateX(3px);
        }

        .sidebar-nav a.active {
            background: var(--sidebar-active);
            color: var(--brand-orange-dark);
            box-shadow: 0 12px 24px -18px rgba(75, 26, 0, 0.55);
        }

        .sidebar-nav i,
        .sidebar-logout-btn i {
            font-size: 1.05rem;
            width: 1.2rem;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-logout {
            margin-top: auto;
            padding-top: 1rem;
        }

        .sidebar-logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 18px;
            padding: 0.95rem 1rem;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .sidebar-logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .content-area {
            margin-left: 345px;
            min-height: 100vh;
            padding: 2rem 1.6rem 2.8rem;
            transition: margin-left 0.28s ease, padding 0.28s ease;
        }

        .page-shell {
            max-width: 1280px;
        }

        .hero-card {
            background: linear-gradient(135deg, rgba(255, 89, 0, 0.1), rgba(255, 89, 0, 0.03));
            border: 1px solid rgba(255, 89, 0, 0.12);
            border-radius: 28px;
            padding: 1.75rem 1.75rem 1.6rem;
            margin-bottom: 1.6rem;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(255, 89, 0, 0.12);
            color: var(--brand-orange);
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 0.95rem;
        }

        .hero-title {
            font-size: clamp(1.8rem, 2.8vw, 2.55rem);
            line-height: 1.08;
            letter-spacing: -0.04em;
            color: var(--brand-orange);
            margin-bottom: 0.7rem;
        }

        .hero-subtitle {
            max-width: 720px;
            color: #5b6472;
            font-size: 1rem;
            line-height: 1.7;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 1.25rem 1.2rem;
            border: 1px solid #f3e3db;
            box-shadow: 0 18px 38px -28px rgba(31, 41, 55, 0.24);
        }

        .summary-card.soft {
            background: linear-gradient(180deg, #fffaf7, #ffffff);
        }

        .summary-card.solid {
            background: linear-gradient(135deg, var(--brand-orange), #ff7b2f);
            border-color: transparent;
            color: #ffffff;
        }

        .summary-card.warn {
            background: linear-gradient(180deg, #fff4ec, #ffffff);
            border-color: #ffd9c3;
        }

        .summary-label {
            font-size: 0.86rem;
            font-weight: 600;
            color: inherit;
            opacity: 0.8;
            margin-bottom: 0.55rem;
        }

        .summary-value {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.15;
            color: inherit;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1.05fr 1.2fr;
            gap: 1.2rem;
        }

        .panel-card {
            background: #ffffff;
            border-radius: 26px;
            border: 1px solid #f3e3db;
            padding: 1.35rem 1.25rem;
            box-shadow: 0 18px 38px -28px rgba(31, 41, 55, 0.24);
        }

        .panel-title {
            font-size: 1.02rem;
            font-weight: 800;
            color: var(--brand-orange);
            margin-bottom: 0.95rem;
        }

        .action-list,
        .info-list {
            list-style: none;
            display: grid;
            gap: 0.75rem;
        }

        .action-item,
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            color: #495361;
            line-height: 1.55;
        }

        .action-item::before,
        .info-item::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 999px;
            margin-top: 0.45rem;
            flex-shrink: 0;
            background: var(--brand-orange);
            box-shadow: 0 0 0 5px rgba(255, 89, 0, 0.12);
        }

        .section-stack {
            display: grid;
            gap: 1.2rem;
        }

        .app-shell.sidebar-collapsed .sidebar {
            width: 88px;
            padding-inline: 0.7rem;
            box-shadow: 10px 0 24px rgba(225, 79, 0, 0.14);
        }

        .app-shell.sidebar-collapsed .content-area {
            margin-left: 88px;
        }

        .app-shell.sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding: 0.55rem 0 0.9rem;
        }

        .app-shell.sidebar-collapsed .sidebar-brand-link,
        .app-shell.sidebar-collapsed .sidebar-user-info,
        .app-shell.sidebar-collapsed .sidebar-logout {
            display: none;
        }

        .app-shell.sidebar-collapsed .sidebar-toggle {
            margin-left: 0;
        }

        .app-shell.sidebar-collapsed .sidebar-nav {
            margin-top: 0.4rem;
        }

        .app-shell.sidebar-collapsed .sidebar-nav a {
            justify-content: center;
            padding: 0.9rem 0;
            border-radius: 18px;
        }

        .app-shell.sidebar-collapsed .sidebar-nav span {
            display: none;
        }

        .app-shell.sidebar-collapsed .sidebar-nav i {
            width: auto;
            font-size: 1.15rem;
        }

        @media (max-width: 1100px) {
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 860px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                min-height: auto;
                border-radius: 0 0 24px 24px;
                transform: none !important;
                padding: 1rem 0.85rem 0.9rem;
                overflow-x: hidden;
            }

            .content-area {
                margin-left: 0;
                padding: 1.2rem 1rem 2rem;
            }

            .sidebar-toggle {
                display: none;
            }

            .sidebar-brand img {
                width: 108px;
            }

            .sidebar-brand-logo {
                width: 88px;
                height: 68px;
                border-radius: 20px;
            }

            .sidebar-brand-text {
                font-size: 1.15rem;
            }
        }

        @media (max-width: 640px) {
            .sidebar {
                padding: 0.9rem 0.7rem 0.85rem;
            }

            .sidebar-brand {
                gap: 0.45rem;
                padding: 0.45rem 0.2rem 0.9rem;
            }

            .sidebar-brand-main {
                gap: 0.45rem;
                min-width: 0;
                max-width: calc(100% - 2.9rem);
            }

            .sidebar-brand-logo {
                width: 82px;
                height: 62px;
                border-radius: 18px;
            }

            .sidebar-brand img {
                width: 96px;
            }

            .sidebar-brand-text {
                font-size: 0.9rem;
            }

            .sidebar-toggle {
                width: 36px;
                height: 36px;
                border-radius: 12px;
                margin-left: 0.2rem;
                flex: 0 0 36px;
            }

            .sidebar-user-info {
                padding: 0.8rem 0.7rem;
            }

            .sidebar-nav a {
                padding: 0.82rem 0.8rem;
                font-size: 0.92rem;
            }

            .sidebar-logout-btn {
                padding: 0.85rem 0.9rem;
            }

            .hero-card {
                padding: 1.25rem 1.1rem;
                border-radius: 22px;
            }

            .hero-subtitle {
                font-size: 0.94rem;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .content-area {
                padding: 1rem 0.85rem 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="content-area">
            <div class="page-shell">
                <section class="hero-card">
                    <div class="eyebrow">{{ $dashboard['role_name'] }}</div>
                    <h1 class="hero-title">Selamat datang, {{ $user['nama'] }}.</h1>
                    <p class="hero-subtitle">{{ $dashboard['headline'] }}</p>
                </section>

                <section class="summary-grid">
                    @foreach ($dashboard['summary_cards'] as $card)
                        <article class="summary-card {{ $card['tone'] }}">
                            <div class="summary-label">{{ $card['label'] }}</div>
                            <div class="summary-value">{{ $card['value'] }}</div>
                        </article>
                    @endforeach
                </section>

                <section class="content-grid">
                    <article class="panel-card">
                        <h2 class="panel-title">Aksi Cepat</h2>
                        <ul class="action-list">
                            @foreach ($dashboard['quick_actions'] as $action)
                                <li class="action-item">{{ $action }}</li>
                            @endforeach
                        </ul>
                    </article>

                    <div class="section-stack">
                        @foreach ($dashboard['panels'] as $panel)
                            <article class="panel-card">
                                <h2 class="panel-title">{{ $panel['title'] }}</h2>
                                <ul class="info-list">
                                    @foreach ($panel['items'] as $item)
                                        <li class="info-item">{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>
        </main>
    </div>
    <script>
        (function () {
            const appShell = document.getElementById('appShell');
            const toggleButton = document.getElementById('sidebarToggle');

            if (!appShell || !toggleButton || window.innerWidth <= 860) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                appShell.classList.toggle('sidebar-collapsed');
                const expanded = !appShell.classList.contains('sidebar-collapsed');
                toggleButton.setAttribute('aria-expanded', String(expanded));
            });
        })();
    </script>
</body>
</html>
