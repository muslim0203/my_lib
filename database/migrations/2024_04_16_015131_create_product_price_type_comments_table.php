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
        Schema::create('product_price_type_comments', function (Blueprint $table) {
            $table->id();
            $table->string('content_oz');
            $table->string('content_uz');
            $table->string('content_ru');
            $table->integer('price_type_id');
            $table->boolean('enabled')->default(true);
            $table->timestamps();


            $table->foreign('price_type_id')
                ->references('id')
                ->on('product_price_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price_type_comments', function (Blueprint $table) {
            $table->dropForeign(['price_type_id']);
        });

        Schema::dropIfExists('product_price_type_comments');
    }
};
