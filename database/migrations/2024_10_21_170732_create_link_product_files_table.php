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
        Schema::create('link_product_files', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('file_id');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->primary(['product_id','file_id']);
            $table->index(['product_id','file_id']);

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('file_id')->references('id')->on('files');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_product_files', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['file_id']);


            $table->dropIndex(['product_id','file_id']);
        });

        Schema::dropIfExists('link_product_files');
    }
};
