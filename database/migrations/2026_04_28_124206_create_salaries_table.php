<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('generated_by_user_id')->nullable()->constrained('users');
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('allowed_off')->default(4);
            $table->decimal('monthly_salary', 10, 2)->default(0);
            $table->decimal('final_salary', 10, 2)->default(0);
            $table->decimal('advance_deduction', 10, 2)->default(0);
            $table->decimal('payable_salary', 10, 2)->default(0);
            $table->string('file_path');
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->index(['user_id', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
