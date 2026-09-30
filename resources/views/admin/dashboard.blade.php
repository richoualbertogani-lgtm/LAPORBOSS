@extends('admin.layout')

@section('page-title', 'Dashboard')

@section('content')
    {{-- Ringkasan jumlah aspirasi berdasarkan status. --}}
    <div class="stats-grid four">
        <div class="stat-card">
            <span>Total Aspirasi</span>
            <strong>{{ $totalAspirasi }}</strong>
        </div>
        <div class="stat-card">
            <span>Menunggu / Dibaca</span>
            <strong>{{ ($perStatus['diajukan'] ?? 0) + ($perStatus['dibaca'] ?? 0) }}</strong>
        </div>
        <div class="stat-card">
            <span>Sedang Diproses</span>
            <strong>{{ $perStatus['diproses'] ?? 0 }}</strong>
        </div>
        <div class="stat-card">
            <span>Selesai</span>
            <strong class="online">{{ $perStatus['selesai'] ?? 0 }}</strong>
        </div>
    </div>

    {{-- Lima aspirasi terbaru. --}}
    <div class="toolbar spaced">
        <h2 class="sub-title">Aspirasi terbaru</h2>
        <a class="btn primary" href="{{ route('admin.aspirasi.index') }}">Lihat Semua →</a>
    </div>
    <div class="table-card">
        <table>
            <thead>
                <tr><th>Kode</th><th>Judul</th><th>Kategori</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($terbaru as $item)
                    <tr>
                        <td>{{ $item->kode_tiket }}</td>
                        <td><a class="link" href="{{ route('admin.aspirasi.show', $item) }}">{{ $item->judul }}</a></td>
                        <td>{{ $item->kategori->nama_kategori }}</td>
                        <td>@include('partials.status-badge', ['aspirasi' => $item])</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">Belum ada aspirasi masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pintasan data master kategori. --}}
    <div class="admin-panel">
        <div>
            <span class="eyebrow">DATA MASTER</span>
            <h2>Kelola kategori</h2>
            <p>Total {{ $totalKategori }} kategori tersedia untuk pilihan siswa.</p>
        </div>
        <a class="btn primary" href="{{ route('admin.kategori.index') }}">Kelola Kategori →</a>
    </div>
@endsection
