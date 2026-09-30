@extends('admin.layout')

@section('page-title', 'Kelola Kategori')

@section('content')
    {{-- Category list with create, edit, and delete actions. --}}
    <div class="toolbar">
        <div>
            <p class="muted">Data master kategori aspirasi.</p>
        </div>
        <a class="btn primary" href="{{ route('admin.kategori.create') }}">+ Tambah Kategori</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategori as $item)
                    <tr>
                        <td>#{{ $item->id_kategori }}</td>
                        <td><strong>{{ $item->nama_kategori }}</strong></td>
                        <td>{{ Str::limit($item->deskripsi ?: '-', 70) }}</td>
                        <td>{{ $item->created_at?->format('d M Y') }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.kategori.edit', $item) }}">Edit</a>
                            <form
                                method="POST"
                                action="{{ route('admin.kategori.destroy', $item) }}"
                                onsubmit="return confirm('Hapus kategori ini?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button>Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $kategori->links() }}</div>
@endsection
