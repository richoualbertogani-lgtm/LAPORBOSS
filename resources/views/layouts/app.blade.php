<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'LaporBoss' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Navigasi utama. Siswa adalah guest, jadi hanya ada tombol "Masuk" untuk admin. --}}
    <nav class="navbar">
        <a class="brand" href="{{ route('home') }}"><span class="brand-dot">L</span> LaporBoss</a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Beranda</a>
            @if(session('admin_id'))
                <a class="nav-cta" href="{{ route('admin.dashboard') }}">Dashboard</a>
            @else
                <a class="nav-cta" href="{{ route('admin.login') }}">Masuk</a>
            @endif
        </div>
    </nav>

    {{-- Pesan sukses/gagal yang tampil satu kali. --}}
    @include('partials.flash')

    <main class="page">@yield('content')</main>
</body>
</html>
