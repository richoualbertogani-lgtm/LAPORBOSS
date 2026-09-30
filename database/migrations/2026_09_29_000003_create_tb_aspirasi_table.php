<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Store each student submission, its category, and its current status.
        Schema::create('tb_aspirasi', function (Blueprint $table) {
            $table->increments('id_aspirasi');
            $table->unsignedInteger('nis');
            $table->unsignedInteger('id_kategori');
            $table->string('judul');
            $table->text('isi_aspirasi');
            $table->enum('status', ['diajukan', 'diproses', 'selesai'])->default('diajukan');
            $table->dateTime('tanggal');
            $table->unsignedInteger('balasan_admin_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('update_at')->nullable();

            // Keep submissions tied to their student, category, and optional reply author.
            $table->foreign('nis')->references('nis')->on('users')->cascadeOnDelete();
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
            $table->foreign('balasan_admin_id')->references('id_admin')->on('admin')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_aspirasi');
    }
};
