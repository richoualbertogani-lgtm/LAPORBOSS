@extends('admin.layout')

@section('page-title', 'Detail Aspirasi')

@section('content')
    <a class="link back" href="{{ route('admin.aspirasi.index') }}">← Kembali ke daftar</a>

    <div class="detail-grid">
        {{-- Kolom kiri: isi aspirasi dan identitas pengirim. --}}
        <div class="card-form">
            <div class="detail-head">
                <div>
                    <h2>{{ $aspirasi->judul }}</h2>
                    <p class="hint">
                        {{ $aspirasi->kode_tiket }} · {{ $aspirasi->kategori->nama_kategori }}
                    </p>
                </div>
                @include('partials.status-badge', ['aspirasi' => $aspirasi])
            </div>

            <p class="detail-body">{{ $aspirasi->isi_aspirasi }}</p>

            @if ($aspirasi->lampiranUrl())
                <a href="{{ $aspirasi->lampiranUrl() }}" target="_blank" rel="noopener">
                    <img class="detail-photo" src="{{ $aspirasi->lampiranUrl() }}" alt="Lampiran aspirasi">
                </a>
            @endif

            <h3 class="sub-title">Pengirim</h3>
            <p class="hint">
                {{ $aspirasi->user->nama }} · NIS {{ $aspirasi->user->nis }} · {{ $aspirasi->user->rombel }}
            </p>

            {{-- Form ubah status: hanya tampil selama belum selesai. --}}
            @if ($aspirasi->status !== 'selesai')
                <h3 class="sub-title">Perbarui status</h3>
                <form method="POST" action="{{ route('admin.aspirasi.status', $aspirasi) }}" class="form-grid">
                    @csrf
                    @method('PATCH')

                    @if ($errors->any())
                        <div class="form-error">{{ $errors->first() }}</div>
                    @endif

                    <label>
                        Status baru
                        <select name="status" required>
                            @if ($aspirasi->status !== 'diproses')
                                <option value="diproses">Diproses</option>
                            @endif
                            <option value="selesai">Selesai</option>
                        </select>
                    </label>
                    <label>
                        Tanggapan untuk siswa
                        <textarea name="keterangan" rows="3" required>{{ old('keterangan') }}</textarea>
                    </label>
                    <button class="btn primary">Simpan Status</button>
                </form>
            @endif
        </div>

        {{-- Kolom kanan: linimasa dan riwayat penanganan. --}}
        <div class="card-form">
            <h3 class="sub-title first">Linimasa</h3>
            @include('partials.linimasa', ['aspirasi' => $aspirasi])

            <h3 class="sub-title">Riwayat penanganan</h3>
            @foreach ($aspirasi->riwayat as $item)
                <div class="reply">
                    <strong>{{ \App\Models\Aspirasi::STATUS_LABEL[$item->status] }}</strong>
                    <small>{{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB</small>
                    <p>{{ $item->keterangan }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
