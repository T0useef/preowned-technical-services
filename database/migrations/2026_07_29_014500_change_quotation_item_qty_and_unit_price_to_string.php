<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Allow text values like "LS" / "As required" in qty and unit price.
        DB::statement('ALTER TABLE quotation_items MODIFY qty VARCHAR(100) NOT NULL DEFAULT "0"');
        DB::statement('ALTER TABLE quotation_items MODIFY unit_price VARCHAR(100) NOT NULL DEFAULT "0"');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE quotation_items MODIFY qty DECIMAL(10,2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE quotation_items MODIFY unit_price DECIMAL(12,2) NOT NULL DEFAULT 0');
    }
};
