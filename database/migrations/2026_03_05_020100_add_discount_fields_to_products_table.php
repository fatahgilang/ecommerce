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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('discount_price', 10, 2)->nullable()->after('product_price');
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('discount_price');
            $table->date('discount_start_date')->nullable()->after('discount_percentage');
            $table->date('discount_end_date')->nullable()->after('discount_start_date');
            $table->boolean('has_discount')->default(false)->after('discount_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'discount_price',
                'discount_percentage', 
                'discount_start_date',
                'discount_end_date',
                'has_discount'
            ]);
        });
    }
};