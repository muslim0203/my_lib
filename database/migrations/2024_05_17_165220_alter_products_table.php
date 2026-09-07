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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('discount_id')
                ->nullable();

            $table->index(['discount_id']);

            $table->foreign('discount_id')
                ->references('id')
                ->on('product_discounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
            $table->dropIndex(['discount_id']);
            $table->dropColumn('discount_id');
        });
    }
};
