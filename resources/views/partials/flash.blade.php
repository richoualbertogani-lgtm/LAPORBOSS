{{-- Pesan sukses/gagal yang tampil satu kali setelah aksi. --}}
@if (session('success'))
    <div class="flash success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="flash error">{{ session('error') }}</div>
@endif
