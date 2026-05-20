<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hak Akses | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/hak_akses.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="hak-akses-page">
            <div id="hak-akses-content">
                <div class="access-card">
                    <div class="access-header">
                        <i class="bi bi-shield-lock"></i> Pengaturan Hak Akses Menu
                    </div>
                    <div class="access-body">
                        <p class="helper-copy">Centang menu yang ingin dimunculkan untuk tiap level. Saat centang dilepas, menu akan hilang dari sidebar dan akses ke halaman utamanya ikut ditutup.</p>

                        @if (session('success'))
                            <div class="flash-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="flash-error">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('hak_akses.update') }}">
                            @csrf
                            <div class="table-scroll">
                                <table class="access-table">
                                    <thead>
                                        <tr>
                                            <th>Menu</th>
                                            @foreach ($levels as $levelId => $levelName)
                                                <th>{{ $levelName }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($menus as $menu)
                                            <tr>
                                                <td data-label="Menu">{{ $menuLabels[$menu] ?? ucwords(str_replace('_', ' ', $menu)) }}</td>
                                                @foreach ($levels as $levelId => $levelName)
                                                    @php
                                                        $isHakAksesSuperadmin = $menu === 'hak_akses' && (int) $levelId === 3;
                                                        $isDatabaseSuperadmin = $menu === 'database_tools' && (int) $levelId === 3;
                                                    @endphp
                                                    <td data-label="{{ $levelName }}">
                                                        <input
                                                            type="checkbox"
                                                            class="checkbox-style"
                                                            name="permissions[{{ $levelId }}][]"
                                                            value="{{ $menu }}"
                                                            {{ isset($permissions[$levelId][$menu]) && $permissions[$levelId][$menu] ? 'checked' : '' }}
                                                            @disabled($isHakAksesSuperadmin || $isDatabaseSuperadmin)
                                                        >
                                                        @if ($isHakAksesSuperadmin)
                                                            <input type="hidden" name="permissions[3][]" value="hak_akses">
                                                        @endif
                                                        @if ($isDatabaseSuperadmin)
                                                            <input type="hidden" name="permissions[3][]" value="database_tools">
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn-save">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

