<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'LaporBoss' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Main navigation changes depending on the student session. --}}
    <nav class="navbar">
        <a class="brand" href="{{ route('home') }}"><span class="brand-dot">L</span> LaporBoss</a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Beranda</a>
            @if(session('user_id'))
                <a href="#aspirasi">Aspirasi Saya</a>
                <form method="POST" action="{{ route('logout') }}" class="inline-form">@csrf<button class="nav-button">Keluar</button></form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a class="nav-cta" href="{{ route('register') }}">Daftar</a>
            @endif
        </div>
    </nav>

    {{-- Display one-request feedback before rendering the current page. --}}
    @if (session('success'))
        <div class="flash success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash error">{{ session('error') }}</div>
    @endif

    <main class="page">@yield('content')</main>
</body>
</html>
