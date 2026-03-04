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
            // Index untuk pencarian nama produk
            $table->index('product_name', 'idx_product_name');
            
            // Index untuk filter berdasarkan shop
            $table->index('shop_id', 'idx_shop_id');
            
            // Index untuk filter berdasarkan harga
            $table->index('product_price', 'idx_product_price');
            
            // Index untuk filter berdasarkan stock
            $table->index('stock', 'idx_stock');
            
            // Composite index untuk pencarian dan filter kombinasi
            $table->index(['shop_id', 'product_name'], 'idx_shop_product_name');
            $table->index(['product_price', 'stock'], 'idx_price_stock');
            
            // Fulltext index untuk pencarian text yang lebih advanced
            $table->fullText(['product_name', 'product_description'], 'idx_fulltext_search');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop fulltext index first
            $table->dropFullText('idx_fulltext_search');
            
            // Drop composite indexes
            $table->dropIndex('idx_price_stock');
            $table->dropIndex('idx_shop_product_name');
            
            // Drop single column indexes
            $table->dropIndex('idx_stock');
            $table->dropIndex('idx_product_price');
            $table->dropIndex('idx_shop_id');
            $table->dropIndex('idx_product_name');
        });
    }
};
