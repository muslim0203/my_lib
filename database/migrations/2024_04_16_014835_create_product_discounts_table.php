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
        Schema::create('product_discounts', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->smallInteger('discount');
            $table->date('from_expire_at');
            $table->date('to_expire_at');
            $table->boolean('enabled');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->index(['from_expire_at', 'to_expire_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_discounts', function (Blueprint $table) {
            $table->dropIndex(['from_expire_at', 'to_expire_at']);

            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('product_discounts');
    }
};
