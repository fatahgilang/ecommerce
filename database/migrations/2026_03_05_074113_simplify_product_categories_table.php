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
        Schema::table('product_categories', function (Blueprint $table) {
            // Remove redundant columns that duplicate Product table data
            $table->dropColumn(['product_description', 'unit', 'price_per_unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            // Restore columns if needed to rollback
            $table->text('product_description')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('price_per_unit', 10, 2)->nullable();
        });
    }
};