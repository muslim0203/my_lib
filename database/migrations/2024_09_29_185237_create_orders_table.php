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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->index();
            $table->integer('client_id')->index();
            $table->decimal('amount');
            $table->string('transaction_id')->nullable();
            $table->dateTime('transaction_time')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('state')->index();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('client_id')->references('id')->on('users');
            $table->unique(['product_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['product_id']);

            $table->dropIndex(['client_id']);
            $table->dropIndex(['product_id']);
        });

        Schema::dropIfExists('orders');
    }
};
