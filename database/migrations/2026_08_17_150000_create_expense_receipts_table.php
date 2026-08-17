<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('expense_receipts')) {
            Schema::create('expense_receipts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('expense_id')->constrained()->cascadeOnDelete();
                $table->string('file_path');
                $table->string('original_name');
                $table->timestamps();
            });
        }

        if (Schema::hasColumn('expenses', 'receipt_path')) {
            $legacyExpenses = DB::table('expenses')
                ->whereNotNull('receipt_path')
                ->get(['id', 'receipt_path', 'receipt_name']);

            foreach ($legacyExpenses as $expense) {
                DB::table('expense_receipts')->insert([
                    'expense_id' => $expense->id,
                    'file_path' => $expense->receipt_path,
                    'original_name' => $expense->receipt_name ?: basename($expense->receipt_path),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::table('expenses', function (Blueprint $table) {
                $table->dropColumn(['receipt_path', 'receipt_name']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'receipt_path')) {
                $table->string('receipt_path')->nullable()->after('description');
                $table->string('receipt_name')->nullable()->after('receipt_path');
            }
        });

        Schema::dropIfExists('expense_receipts');
    }
};
