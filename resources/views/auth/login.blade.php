@extends('layouts.app')

@section('content')
    {{-- Student sign-in form and links to registration and admin access. --}}
    <div class="auth-shell">
        <div class="auth-card">
            <span class="eyebrow">AKSES SISWA</span>
            <h1>Masuk ke LaporBoss</h1>
            <p class="muted">Gunakan NIS yang sudah didaftarkan.</p>

            @if ($errors->any())
                <div class="form-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="form-grid">
                @csrf

                <label>
                    NIS
                    <input
                        type="number"
                        name="nis"
                        value="{{ old('nis') }}"
                        min="1"
                        max="4294967295"
                        required
                        autofocus
                    >
                </label>

                <button class="btn primary full">Masuk</button>
            </form>

            <p class="form-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </p>
            <a class="admin-link" href="{{ route('admin.login') }}">Masuk sebagai admin →</a>
        </div>
    </div>
@endsection
