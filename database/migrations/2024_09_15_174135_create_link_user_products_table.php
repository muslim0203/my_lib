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
        Schema::create('link_user_products', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('product_id');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->primary(['user_id', 'product_id']);
            $table->index(['user_id', 'product_id']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_user_products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);

            $table->dropIndex(['user_id', 'product_id']);
        });

        Schema::dropIfExists('link_user_products');
    }
};
