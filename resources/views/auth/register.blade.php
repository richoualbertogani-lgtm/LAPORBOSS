@extends('layouts.app')

@section('content')
    {{-- Student registration form and validation feedback. --}}
    <div class="auth-shell">
        <div class="auth-card wide">
            <span class="eyebrow">REGISTRASI</span>
            <h1>Buat akun siswa</h1>
            <p class="muted">Isi data berikut untuk mulai menyampaikan aspirasi.</p>

            @if ($errors->any())
                <div class="form-error" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="form-grid">
                @csrf

                <label for="nis">NIS (Nomor Induk Siswa)</label>
                <input
                    id="nis"
                    type="number"
                    name="nis"
                    value="{{ old('nis') }}"
                    min="1"
                    max="4294967295"
                    required
                >

                <label for="nama">Nama lengkap</label>
                <input id="nama" name="nama" value="{{ old('nama') }}" required>

                <label for="rombel">Rombel (Rombongan Belajar)</label>
                <input
                    id="rombel"
                    name="rombel"
                    value="{{ old('rombel') }}"
                    placeholder="Contoh: XI RPL 1"
                    required
                >

                <button type="submit" class="btn primary full">Buat Akun</button>
            </form>

            <p class="form-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
            </p>
        </div>
    </div>
@endsection
