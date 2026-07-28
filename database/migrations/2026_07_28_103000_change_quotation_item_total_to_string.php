<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Allow text values like "LS" / "Included" in line totals.
        DB::statement('ALTER TABLE quotation_items MODIFY total VARCHAR(100) NOT NULL DEFAULT "0"');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE quotation_items MODIFY total DECIMAL(12,2) NOT NULL DEFAULT 0');
    }
};
