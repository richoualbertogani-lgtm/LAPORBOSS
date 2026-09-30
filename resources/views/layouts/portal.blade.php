<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'LaporBoss' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="portal-body" @yield('body-attr')>
    {{-- Header halaman: judul di kiri, tautan cepat di kanan. --}}
    <header class="portal-header">
        <div>
            <h1>@yield('judul')</h1>
            <p>@yield('subjudul')</p>
        </div>
        <nav class="portal-nav">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('aspirasi.create') }}">Buat Aspirasi</a>
            <a href="{{ route('aspirasi.lacak') }}">Lacak Aspirasi</a>
        </nav>
    </header>

    <main class="portal-main">
        @include('partials.flash')
        @yield('content')
    </main>
</body>
</html>
