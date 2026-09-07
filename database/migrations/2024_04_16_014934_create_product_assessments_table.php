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
        Schema::create('product_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->smallInteger('level');
            $table->integer('author_id');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->foreign('author_id')
                ->references('id')
                ->on('authors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_assessments', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('product_assessments');
    }
};
