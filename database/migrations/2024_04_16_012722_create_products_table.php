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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title_oz');
            $table->string('title_uz');
            $table->string('title_ru');
            $table->text('description_oz');
            $table->text('description_uz');
            $table->text('description_ru');
            $table->integer('author_id');
            $table->string('author_type');
            $table->integer('type_id');
            $table->integer('status_id');
            $table->integer('step_id');
            $table->integer('parent_id')->nullable();
            $table->string('size');
            $table->integer('wrapper_file_id');
            $table->string('wrapper_file_name');
            $table->integer('source_file_id');
            $table->string('source_file_name');
            $table->string('state');
            $table->integer('price_id')->nullable();
            $table->integer('price_type_id');
            $table->integer('confirm_author_id');
            $table->date('last_updated_date');
            $table->date('create_date');
            $table->string('extra_authors');
            $table->timestamps();

            $table->foreign('type_id')
                ->references('id')
                ->on('enum_product_types');

            $table->foreign('status_id')
                ->references('id')
                ->on('enum_product_status');

            $table->foreign('step_id')
                ->references('id')
                ->on('process_steps');

            $table->foreign('parent_id')
                ->references('id')
                ->on('products');

            $table->foreign('wrapper_file_id')
                ->references('id')
                ->on('files');

            $table->foreign('source_file_id')
                ->references('id')
                ->on('files');

            $table->foreign('price_type_id')
                ->references('id')
                ->on('product_price_types');

            $table->foreign('confirm_author_id')
                ->references('id')
                ->on('users');

            $table->index(['title_oz', 'title_uz', 'title_ru']);
            $table->index(['description_oz', 'description_uz', 'description_ru']);
            $table->index(['extra_authors']);
            $table->index(['create_date']);
            $table->index(['last_updated_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['last_updated_date']);
            $table->dropIndex(['create_date']);
            $table->dropIndex(['extra_authors']);
            $table->dropIndex(['description_oz', 'description_uz', 'description_ru']);
            $table->dropIndex(['title_oz', 'title_uz', 'title_ru']);

            $table->dropForeign(['confirm_author_id']);
            $table->dropForeign(['price_type_id']);
            $table->dropForeign(['source_file_id']);
            $table->dropForeign(['wrapper_file_id']);
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['step_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['type_id']);
        });

        Schema::dropIfExists('products');
    }
};
