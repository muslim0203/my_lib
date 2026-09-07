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
            $table->smallInteger('payment_system_service_percentage')
                ->index()
                ->nullable()
                ->default(0);
            $table->decimal('payment_system_service_money_amount')
                ->index()
                ->nullable();

            $table->renameColumn('merchant_price_percentage', 'web_service_price_percentage');
            $table->decimal('web_service_money_amount')
                ->index()
                ->nullable();

            $table->renameColumn('merchant_price_amount', 'merchant_money_amount');
            $table->renameColumn('amount', 'order_price');
            $table->renameColumn('merchant_status', 'status');
            $table->renameColumn('income_price_amount', 'income_money_amount');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_orders', function (Blueprint $table) {
            $table->renameColumn('web_service_price_percentage', 'merchant_price_percentage');
            $table->renameColumn('merchant_money_amount', 'merchant_price_amount');
            $table->renameColumn('order_price', 'amount');
            $table->renameColumn('status', 'merchant_status');
            $table->renameColumn('income_money_amount', 'income_price_amount');

            $table->dropIndex(['payment_system_service_percentage']);
            $table->dropColumn('payment_system_service_percentage');

            $table->dropIndex(['payment_system_service_money_amount']);
            $table->dropColumn('payment_system_service_money_amount');

            $table->dropIndex(['web_service_money_amount']);
            $table->dropColumn('web_service_money_amount');
        });
    }
};
