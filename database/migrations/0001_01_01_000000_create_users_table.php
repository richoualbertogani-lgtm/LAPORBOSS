<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel identitas pengirim aspirasi dan tabel sesi.
     *
     * Tabel `users` HANYA menyimpan identitas siswa (guest) yang mengisi form.
     * Tidak ada password, email, maupun remember_token, sehingga siswa tidak
     * perlu mendaftar atau login untuk mengirim aspirasi.
     */
    public function up(): void
    {
        // Identitas siswa pengirim aspirasi (diisi otomatis saat form dikirim).
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedInteger('nis')->primary();
            $table->string('nama');
            $table->string('rombel', 50);
            $table->timestamps();
        });

        // Sesi dipakai admin; kolom user_id dibiarkan kosong untuk guest.
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Hapus tabel yang dibuat oleh migration ini.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
