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
        Schema::create('link_product_genres', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('genre_id');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->primary('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('enum_product_genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_product_genres', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['genre_id']);
        });


        Schema::dropIfExists('link_product_genres');
    }
};
