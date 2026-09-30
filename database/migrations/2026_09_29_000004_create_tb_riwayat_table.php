<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel riwayat: setiap perubahan status tercatat beserta waktunya.
     */
    public function up(): void
    {
        Schema::create('tb_riwayat', function (Blueprint $table) {
            $table->increments('id_riwayat');
            $table->unsignedInteger('aspirasi_id');
            $table->enum('status', ['diajukan', 'dibaca', 'diproses', 'selesai']);
            $table->text('keterangan');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('update_at')->nullable();

            // Kosong (null) untuk catatan otomatis "diajukan" yang dibuat oleh siswa.
            $table->unsignedInteger('id_admin')->nullable();

            $table->foreign('aspirasi_id')->references('id_aspirasi')->on('tb_aspirasi')->cascadeOnDelete();
            $table->foreign('id_admin')->references('id_admin')->on('admin')->nullOnDelete();
        });
    }

    /**
     * Hapus tabel riwayat.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat');
    }
};
