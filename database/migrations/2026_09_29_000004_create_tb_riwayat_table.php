<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Record each status change with the administrator who handled it.
        Schema::create('tb_riwayat', function (Blueprint $table) {
            $table->increments('id_riwayat');
            $table->unsignedInteger('aspirasi_id');
            $table->enum('status', ['diajukan', 'diproses', 'selesai']);
            $table->text('keterangan');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('update_at')->nullable();
            $table->unsignedInteger('id_admin');

            $table->foreign('aspirasi_id')->references('id_aspirasi')->on('tb_aspirasi')->cascadeOnDelete();
            $table->foreign('id_admin')->references('id_admin')->on('admin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat');
    }
};
