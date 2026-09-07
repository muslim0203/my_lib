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
        Schema::table('products_orders', function (Blueprint $table) {
            $table->decimal('amount')->default(0);
            $table->decimal('merchant_price_amount')->default(0);
            $table->decimal('merchant_price_percentage')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_orders', function (Blueprint $table) {
            $table->dropColumn('merchant_price_percentage');
            $table->dropColumn('merchant_price_amount');
            $table->dropColumn('amount');
        });
    }
};
