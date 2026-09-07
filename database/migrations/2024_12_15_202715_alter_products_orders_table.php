<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products_orders', function (Blueprint $table) {
            $table->renameColumn('income_money_amount', 'web_service_and_payment_system_money_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_orders', function (Blueprint $table) {
            $table->renameColumn('web_service_and_payment_system_money_amount', 'income_money_amount');
        });
    }
};
