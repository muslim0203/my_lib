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
        Schema::table('link_product_genres', function (Blueprint $table) {
            $table->dropPrimary('link_product_genres_pkey');

            $table->primary(['product_id', 'genre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_product_genres', function (Blueprint $table) {
            $table->dropPrimary(['product_id', 'genre_id']);

            $table->primary(['product_id']);
        });
    }
};
