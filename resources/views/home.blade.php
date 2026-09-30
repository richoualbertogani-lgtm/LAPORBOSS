@extends('layouts.app')

@section('content')
    {{-- Bagian utama: ajakan menyampaikan aspirasi tanpa perlu akun. --}}
    <section class="hero">
        <div>
            <h1>Sampaikan aspirasi,<br><span>wujudkan perubahan.</span></h1>
            <p>
                LaporBoss membantu siswa menyampaikan ide, keluhan, dan aspirasi secara terstruktur agar dapat
                ditindaklanjuti dengan transparan.
            </p>
            <div class="hero-actions">
                {{-- Tombol utama membuka form "Buat Aspirasi". --}}
                <a class="btn primary" href="{{ route('aspirasi.create') }}">Mulai Sampaikan Aspirasi</a>
                <a class="btn secondary" href="{{ route('aspirasi.lacak') }}">Lacak Aspirasi</a>
            </div>
        </div>

        {{-- Kartu ringkasan di sisi kanan. --}}
        <div class="hero-card">
            <div class="mini-icon">✓</div>
            <strong>Ruang suara siswa</strong>
            <p>Setiap aspirasi tercatat dan dapat dipantau statusnya.</p>
            <div class="stat-row"><span>Transparan</span><span>Terstruktur</span></div>
        </div>
    </section>

    {{-- Penjelasan alur kerja aplikasi. --}}
    <section class="section" id="fitur">
        <div class="section-heading">
            <div>
                <span class="eyebrow">FITUR UTAMA</span>
                <h2>Semua dimulai dari satu suara.</h2>
            </div>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <div class="feature-number">01</div>
                <h3>Buat Aspirasi</h3>
                <p>Tuliskan judul, isi aspirasi, kategori, dan tanggal dengan mudah.</p>
            </article>
            <article class="feature-card">
                <div class="feature-number">02</div>
                <h3>Pantau Proses</h3>
                <p>Lihat perkembangan aspirasi dari diajukan sampai selesai.</p>
            </article>
            <article class="feature-card">
                <div class="feature-number">03</div>
                <h3>Respons Admin</h3>
                <p>Admin dapat memberi tanggapan dan riwayat penanganan.</p>
            </article>
        </div>
    </section>
@endsection
