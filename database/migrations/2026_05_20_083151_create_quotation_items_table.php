<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->string('item_type', 20)->default('main_item');
            $table->string('display_number', 20)->nullable();
            $table->string('unit', 50)->nullable();
            $table->string('qty', 100)->default('0');
            $table->string('unit_price', 100)->default('0');
            $table->string('total', 100)->default('0');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
