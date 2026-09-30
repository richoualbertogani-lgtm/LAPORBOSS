<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel aspirasi beserta status dan waktu setiap tahap penanganan.
     */
    public function up(): void
    {
        Schema::create('tb_aspirasi', function (Blueprint $table) {
            $table->increments('id_aspirasi');

            // Kode tiket dipakai siswa (guest) untuk melacak aspirasinya tanpa login.
            $table->string('kode_tiket', 20)->unique();

            // Identitas pengirim, kategori, dan isi aspirasi.
            $table->unsignedInteger('nis');
            $table->unsignedInteger('id_kategori');
            $table->string('judul');
            $table->text('isi_aspirasi');
            $table->string('lampiran')->nullable(); // Path foto pada disk "public".

            // Status: diajukan -> dibaca -> diproses -> selesai.
            $table->enum('status', ['diajukan', 'dibaca', 'diproses', 'selesai'])->default('diajukan');
            $table->dateTime('tanggal');              // Waktu aspirasi diajukan.
            $table->dateTime('dibaca_at')->nullable();   // Waktu pertama kali dibaca admin.
            $table->dateTime('diproses_at')->nullable(); // Waktu mulai diproses.
            $table->dateTime('selesai_at')->nullable();  // Waktu selesai dikerjakan.

            $table->unsignedInteger('balasan_admin_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('update_at')->nullable();

            // Relasi ke pengirim, kategori, dan admin yang terakhir menangani.
            $table->foreign('nis')->references('nis')->on('users')->cascadeOnDelete();
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
            $table->foreign('balasan_admin_id')->references('id_admin')->on('admin')->nullOnDelete();
        });
    }

    /**
     * Hapus tabel aspirasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_aspirasi');
    }
};
