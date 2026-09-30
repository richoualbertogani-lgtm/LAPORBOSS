@extends('layouts.portal')

@section('judul', 'Buat Aspirasi')
@section('subjudul', 'Sampaikan laporan atau ide Anda dengan jelas')

@section('content')
    <form
        id="form-aspirasi"
        class="card-form"
        method="POST"
        action="{{ route('aspirasi.store') }}"
        enctype="multipart/form-data"
        novalidate
    >
        @csrf

        <h2>Detail aspirasi</h2>
        <p class="hint">Kolom bertanda * wajib diisi</p>

        {{-- Identitas siswa: cukup diisi, tidak perlu login. --}}
        <label class="field-title">Identitas siswa *</label>
        <div class="info-banner">
            <span class="info-icon">👤</span>
            Identitas digunakan sekolah untuk verifikasi dan tindak lanjut aspirasi Anda.
        </div>

        <div class="form-cols">
            <div class="field">
                <label for="nis">NIS *</label>
                <input id="nis" name="nis" type="text" inputmode="numeric" value="{{ old('nis') }}" placeholder="Contoh: 12511334" required>
                @error('nis') <span class="field-error">{{ $message }}</span> @else <span class="hint">Masukkan NIS yang terdaftar di sekolah.</span> @enderror
            </div>

            <div class="field">
                <label for="nama">Nama *</label>
                <input id="nama" name="nama" type="text" value="{{ old('nama') }}" placeholder="Nama lengkap" required>
                @error('nama') <span class="field-error">{{ $message }}</span> @else <span class="hint">Gunakan nama lengkap sesuai data sekolah.</span> @enderror
            </div>

            <div class="field">
                <label for="rombel">Rombel *</label>
                <input id="rombel" name="rombel" type="text" value="{{ old('rombel') }}" placeholder="Contoh: PPLG XI-3" required>
                @error('rombel') <span class="field-error">{{ $message }}</span> @else <span class="hint">Pilih rombel yang sesuai dengan kelas Anda.</span> @enderror
            </div>

            <div class="field">
                <label for="id_kategori">Kategori *</label>
                <select id="id_kategori" name="id_kategori" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id_kategori }}" @selected(old('id_kategori') == $item->id_kategori)>
                            {{ $item->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_kategori') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="field">
                <label for="judul">Judul aspirasi *</label>
                <input id="judul" name="judul" type="text" value="{{ old('judul') }}" placeholder="Ringkas inti aspirasi Anda" required>
                @error('judul') <span class="field-error">{{ $message }}</span> @else <span class="hint">Gunakan judul singkat yang menggambarkan inti aspirasi.</span> @enderror
            </div>

            <div class="field">
                <label for="isi_aspirasi">Isi aspirasi *</label>
                <textarea id="isi_aspirasi" name="isi_aspirasi" rows="5" placeholder="Jelaskan aspirasi Anda" required>{{ old('isi_aspirasi') }}</textarea>
                @error('isi_aspirasi') <span class="field-error">{{ $message }}</span> @else <span class="hint">Sertakan lokasi dan waktu kejadian agar lebih mudah ditindaklanjuti.</span> @enderror
            </div>
        </div>

        {{-- Lampiran foto (opsional). Pratinjau dan tombol Ganti/Hapus dikelola aspirasi-form.js. --}}
        <label class="field-title">Lampiran visual</label>
        <div class="attachment" id="lampiran-box">
            <input id="lampiran" name="lampiran" type="file" accept="image/jpeg,image/png,image/webp" hidden>

            {{-- Tampil saat belum ada foto. --}}
            <div class="attachment-empty" id="lampiran-kosong">
                <span class="hint">Belum ada foto. Format JPG, PNG, atau WEBP, maksimal 2 MB.</span>
                <button type="button" class="btn outline small" id="lampiran-pilih">Pilih Foto</button>
            </div>

            {{-- Tampil setelah foto dipilih. --}}
            <div class="attachment-file" id="lampiran-terpilih" hidden>
                <img id="lampiran-thumb" alt="Pratinjau lampiran">
                <div class="attachment-info">
                    <strong id="lampiran-nama"></strong>
                    <span class="hint" id="lampiran-ukuran"></span>
                </div>
                <button type="button" class="btn outline small" id="lampiran-ganti">Ganti</button>
                <button type="button" class="icon-danger" id="lampiran-hapus" aria-label="Hapus lampiran">🗑</button>
            </div>
        </div>
        <span class="field-error" id="lampiran-error">@error('lampiran'){{ $message }}@enderror</span>

        {{-- Aksi form. --}}
        <div class="form-bottom">
            <span class="hint" id="status-draf">Draf tersimpan otomatis</span>
            <div class="form-actions">
                <a class="btn outline" id="tombol-batal" href="{{ route('home') }}">Batal</a>
                <button type="submit" class="btn primary">➤ Kirim Aspirasi</button>
            </div>
        </div>
    </form>
@endsection
