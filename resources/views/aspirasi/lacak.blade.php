@extends('layouts.portal')

@section('judul', 'Lacak Aspirasi')
@section('subjudul', 'Pantau kapan aspirasi dibaca, diproses, dan selesai dikerjakan')

{{-- Setelah aspirasi terkirim, draf form yang tersimpan di peramban dihapus. --}}
@if (session('success'))
    @section('body-attr', 'data-hapus-draf')
@endif

@section('content')
    {{-- Form pencarian memakai kode tiket yang diberikan setelah aspirasi dikirim. --}}
    <form class="card-form narrow" method="GET" action="{{ route('aspirasi.lacak') }}">
        <h2>Masukkan kode tiket</h2>
        <p class="hint">Kode tiket diberikan setelah Anda mengirim aspirasi, contoh: ASP-7K3M9XQ2.</p>
        <div class="search-row">
            <input name="kode" type="text" value="{{ $aspirasi?->kode_tiket }}" placeholder="ASP-XXXXXXXX" required>
            <button class="btn primary" type="submit">Lacak</button>
        </div>
    </form>

    @if ($aspirasi)
        <div class="card-form">
            <div class="detail-head">
                <div>
                    <h2>{{ $aspirasi->judul }}</h2>
                    <p class="hint">
                        Kode tiket <strong>{{ $aspirasi->kode_tiket }}</strong>
                        · {{ $aspirasi->kategori->nama_kategori }}
                    </p>
                </div>
                @include('partials.status-badge', ['aspirasi' => $aspirasi])
            </div>

            <p class="detail-body">{{ $aspirasi->isi_aspirasi }}</p>

            @if ($aspirasi->lampiranUrl())
                <img class="detail-photo" src="{{ $aspirasi->lampiranUrl() }}" alt="Lampiran aspirasi">
            @endif

            <h3 class="sub-title">Perkembangan aspirasi</h3>
            @include('partials.linimasa', ['aspirasi' => $aspirasi])

            {{-- Tanggapan admin dari riwayat (yang dibuat oleh admin saja). --}}
            <h3 class="sub-title">Tanggapan admin</h3>
            @php($tanggapan = $aspirasi->riwayat->whereNotNull('id_admin')->where('status', '!=', 'dibaca'))
            @forelse ($tanggapan as $item)
                <div class="reply">
                    <strong>{{ \App\Models\Aspirasi::STATUS_LABEL[$item->status] }}</strong>
                    <small>{{ $item->created_at->translatedFormat('d F Y, H:i') }} WIB</small>
                    <p>{{ $item->keterangan }}</p>
                </div>
            @empty
                <p class="hint">Belum ada tanggapan dari admin.</p>
            @endforelse
        </div>
    @endif
@endsection
