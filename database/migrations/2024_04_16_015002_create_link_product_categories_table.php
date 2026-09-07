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
        Schema::create('link_product_categories', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('category_id');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->foreign('category_id')
                ->references('id')
                ->on('enum_categories');

            $table->primary('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_product_categories', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('link_product_categories');
    }
};
