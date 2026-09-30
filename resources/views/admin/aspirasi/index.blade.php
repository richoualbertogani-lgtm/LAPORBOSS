@extends('admin.layout')

@section('page-title', 'Aspirasi Siswa')

@section('content')
    {{-- Tab filter berdasarkan status. --}}
    <div class="filter-tabs">
        {{-- Tab "Aktif" menyembunyikan aspirasi yang sudah selesai. --}}
        <a class="{{ ! $status ? 'active' : '' }}" href="{{ route('admin.aspirasi.index') }}">Aktif</a>
        @foreach (\App\Models\Aspirasi::STATUS_LABEL as $kode => $label)
            <a class="{{ $status === $kode ? 'active' : '' }}" href="{{ route('admin.aspirasi.index', ['status' => $kode]) }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pengirim</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Diajukan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aspirasi as $item)
                    <tr>
                        <td>{{ $item->kode_tiket }}</td>
                        <td>
                            {{ $item->user->nama }}<br>
                            <small class="muted">{{ $item->user->rombel }}</small>
                        </td>
                        <td><a class="link" href="{{ route('admin.aspirasi.show', $item) }}">{{ $item->judul }}</a></td>
                        <td>{{ $item->kategori->nama_kategori }}</td>
                        <td>{{ $item->tanggal->translatedFormat('d M Y, H:i') }}</td>
                        <td>
                            @include('partials.status-badge', ['aspirasi' => $item])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">Belum ada aspirasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $aspirasi->links() }}</div>
@endsection
