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
        Schema::create('comment_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('comment_id');
            $table->integer('product_id');
            $table->boolean('positive');
            $table->integer('author_id');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('comment_id')
                ->references('id')
                ->on('product_comments');

            $table->foreign('product_id')
                ->references('id')
                ->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comment_assessments', function (Blueprint $table) {
            $table->dropForeign(['product_id']);

            $table->dropForeign(['comment_id']);
        });

        Schema::dropIfExists('comment_assessments');
    }
};
