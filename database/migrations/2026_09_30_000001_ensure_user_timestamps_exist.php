<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $missingColumns = array_values(array_filter(
            ['created_at', 'updated_at'],
            fn (string $column) => ! Schema::hasColumn('users', $column)
        ));

        if ($missingColumns === []) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($missingColumns): void {
            foreach ($missingColumns as $column) {
                $table->timestamp($column)->nullable();
            }
        });
    }

    public function down(): void
    {
        // The base users migration owns these columns on clean installations.
    }
};