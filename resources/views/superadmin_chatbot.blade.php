<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot Superadmin | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/superadmin_chatbot.css') }}">
</head>
<body>
<div class="app-shell" id="appShell">
    @include('header')

    <main class="chatbot-workspace-page">
        <div class="page-shell">
            <section class="workspace-hero">
                <div class="eyebrow"><i class="bi bi-robot"></i> Chatbot Superadmin</div>
                <h1>Chatbot Superadmin</h1>
                <p>Kelola bantuan cepat InfraSPH dari satu ruang kerja khusus superadmin.</p>
            </section>
        </div>
    </main>

    @include('chatbot')
</div>
</body>
</html>
