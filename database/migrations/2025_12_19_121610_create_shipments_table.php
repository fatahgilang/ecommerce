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
        Schema::create('shipments', function (Blueprint $table) {
             $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('shipment_type');
            $table->string('payment_method');
            $table->text('customers_address');
            $table->decimal('product_price', 10, 2);
            $table->decimal('delivery_cost', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->enum('status', ['pending', 'shipped', 'delivered'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
