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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('transaction_id')->nullable()->after('status')->constrained()->onDelete('set null');
            $table->foreignId('processed_by')->nullable()->after('transaction_id')->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable()->after('processed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['transaction_id', 'processed_by', 'processed_at']);
        });
    }
};