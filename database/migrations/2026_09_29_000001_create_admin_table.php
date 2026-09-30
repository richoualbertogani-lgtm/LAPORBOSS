<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Administrator credentials use the existing legacy column names.
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('id_admin');
            $table->string('email_admin')->unique();
            $table->string('password_admin');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('update_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
