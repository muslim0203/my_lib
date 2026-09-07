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
            $table->dropColumn('author_type');

            $table->foreign('author_id')
                ->references('id')
                ->on('users');

            $table->decimal('price_value', 9);

            $table->index(['price_value']);

            $table->index(['author_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['author_id']);
            $table->dropIndex(['price_value']);

            $table->dropForeign(['author_id']);

            $table->string('author_type')->nullable();
        });
    }
};
