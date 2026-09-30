@extends('layouts.app')

@section('content')
    {{-- Administrator sign-in form. --}}
    <div class="auth-shell">
        <div class="auth-card">
            <span class="eyebrow">ADMIN</span>
            <h1>Masuk Admin</h1>
            <p class="muted">Kelola aspirasi dan kategori dari dashboard admin.</p>

            @if ($errors->any())
                <div class="form-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="form-grid">
                @csrf

                <label>
                    Email admin
                    <input
                        type="email"
                        name="email_admin"
                        value="{{ old('email_admin') }}"
                        required
                        autofocus
                    >
                </label>
                <label>
                    Password
                    <input type="password" name="password" required>
                </label>
                <button class="btn primary full">Masuk Admin</button>
            </form>
        </div>
    </div>
@endsection
