<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Admin LaporBoss' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        {{-- Sidebar navigation and administrator sign-out. --}}
        <aside class="sidebar">
            <a class="brand side-brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-dot">L</span> LaporBoss
            </a>
            <p class="side-label">ADMIN PANEL</p>
            <a
                class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}"
            >▦ Dashboard</a>
            <a
                class="side-link {{ request()->routeIs('admin.aspirasi.*') ? 'active' : '' }}"
                href="{{ route('admin.aspirasi.index') }}"
            >✉ Aspirasi</a>
            <a
                class="side-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}"
                href="{{ route('admin.kategori.index') }}"
            >◫ Kategori</a>
            <div class="side-spacer"></div>
            <a class="side-link" href="{{ route('home') }}">← Situs Utama</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="side-link side-button">↪ Keluar</button>
            </form>
        </aside>

        {{-- Page heading, flash feedback, and child-page content. --}}
        <div class="admin-main">
            <header class="admin-header">
                <div>
                    <span class="eyebrow">LAPORBOSS ADMIN</span>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <span class="admin-badge">Administrator</span>
            </header>

            @include('partials.flash')

            @yield('content')
        </div>
    </body>
</html>
