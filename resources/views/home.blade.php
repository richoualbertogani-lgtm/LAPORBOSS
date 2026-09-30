@extends('layouts.app')

@section('content')
    {{-- Welcome content and calls to action depend on the student session. --}}
    <section class="hero">
        <div>
            <span class="eyebrow">PORTAL ASPIRASI SEKOLAH</span>
            <h1>Sampaikan aspirasi,<br><span>wujudkan perubahan.</span></h1>
            <p>
                LaporBoss membantu siswa menyampaikan ide, keluhan, dan aspirasi secara terstruktur agar dapat
                ditindaklanjuti dengan transparan.
            </p>
            <div class="hero-actions">
                @if ($user)
                    <a class="btn primary" href="#buat-aspirasi">+ Buat Aspirasi</a>
                    <a class="btn secondary" href="#aspirasi">Lihat Aspirasi Saya</a>
                @else
                    <a class="btn primary" href="{{ route('register') }}">Mulai Sampaikan Aspirasi</a>
                    <a class="btn secondary" href="{{ route('login') }}">Sudah punya akun?</a>
                @endif
            </div>
        </div>
        <div class="hero-card">
            <div class="mini-icon">✓</div>
            <strong>Ruang suara siswa</strong>
            <p>Setiap aspirasi tercatat dan dapat dipantau statusnya.</p>
            <div class="stat-row"><span>Transparan</span><span>Terstruktur</span></div>
        </div>
    </section>

    {{-- Explain the main aspiration workflow. --}}
    <section class="section" id="aspirasi">
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

    {{-- Categories are loaded from the database; show guidance when none exist. --}}
    <section class="section categories" id="buat-aspirasi">
        <div class="section-heading">
            <div>
                <span class="eyebrow">KATEGORI</span>
                <h2>Pilih ruang aspirasi yang sesuai.</h2>
            </div>
        </div>
        <div class="category-list">
            @forelse ($kategori as $item)
                <div class="category-item">
                    <div>
                        <strong>{{ $item->nama_kategori }}</strong>
                        <p>{{ $item->deskripsi ?: 'Aspirasi terkait lingkungan sekolah.' }}</p>
                    </div>
                    <span>→</span>
                </div>
            @empty
                <div class="empty">Belum ada kategori. Admin dapat menambahkannya melalui dashboard.</div>
            @endforelse
        </div>
    </section>
@endsection
