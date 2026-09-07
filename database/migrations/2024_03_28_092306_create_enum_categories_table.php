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
        Schema::create('enum_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_oz');
            $table->string('name_uz');
            $table->string('name_ru');
            $table->integer('parent_id')->nullable();
            $table->boolean('enabled')->default(true);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            $table->unique(['name_oz', 'name_ru', 'name_uz']);

            $table->foreign('parent_id')
                ->references('id')
                ->on('enum_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enum_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });

        Schema::dropIfExists('enum_categories');
    }
};
