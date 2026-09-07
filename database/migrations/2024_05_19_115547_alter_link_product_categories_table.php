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
        Schema::table('link_product_categories', function (Blueprint $table) {
            $table->dropPrimary(['product_id']);

            $table->primary(['product_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_product_categories', function (Blueprint $table) {
            $table->dropPrimary(['product_id', 'category_id']);

            $table->primary(['product_id']);
        });
    }
};
