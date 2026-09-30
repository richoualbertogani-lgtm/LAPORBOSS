@extends('admin.layout')

@section('page-title', 'Dashboard')

@section('content')
    {{-- Overview counts and the main category-management shortcut. --}}
    <div class="stats-grid">
        <div class="stat-card">
            <span>Total Pengguna</span>
            <strong>{{ $totalUsers }}</strong>
        </div>
        <div class="stat-card">
            <span>Total Kategori</span>
            <strong>{{ $totalKategori }}</strong>
        </div>
        <div class="stat-card">
            <span>Status Sistem</span>
            <strong class="online">Aktif</strong>
        </div>
    </div>

    <div class="admin-panel">
        <div>
            <span class="eyebrow">DATA MASTER</span>
            <h2>Kelola data aplikasi</h2>
            <p>Komponen CRUD data master tanpa relasi tersedia pada menu kategori.</p>
        </div>
        <a class="btn primary" href="{{ route('admin.kategori.index') }}">Kelola Kategori →</a>
    </div>
@endsection
