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
        Schema::create('products_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->index();
            $table->integer('customer_id')->index();
            $table->integer('author_id')->index();
            $table->string('payment_type');
            $table->string('transaction_id');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('customer_id')->references('id')->on('users');
            $table->unique(['product_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_orders', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('products_orders');
    }
};
