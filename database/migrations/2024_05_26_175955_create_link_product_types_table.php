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
            $table->dropForeign(['type_id']);

            $table->dropColumn('type_id');
        });

        Schema::create('link_product_types', function (Blueprint $table) {
            $table->integer('product_id');
            $table->integer('type_id')->index();
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->primary(['product_id', 'type_id']);

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('type_id')
                ->references('id')
                ->on('enum_product_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_product_types', function (Blueprint $table) {

            $table->dropForeign(['type_id']);
            $table->dropForeign(['product_id']);

            $table->dropIndex(['type_id']);

            $table->dropPrimary(['product_id', 'type_id']);

        });

        Schema::dropIfExists('link_product_types');

        Schema::table('products', function (Blueprint $table) {
            $table->integer('type_id')->nullable();

            $table->foreign(['type_id'])
                ->references('id')
                ->on('enum_product_types');
        });
    }
};
