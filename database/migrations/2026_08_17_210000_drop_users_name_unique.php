<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (Schema::getIndexes('users') as $index) {
            $columns = $index['columns'] ?? [];
            $isNameUnique = ($index['unique'] ?? false)
                && !($index['primary'] ?? false)
                && $columns === ['name'];

            if (!$isNameUnique) {
                continue;
            }

            Schema::table('users', function (Blueprint $table) use ($index) {
                $table->dropUnique($index['name']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('name');
        });
    }
};
