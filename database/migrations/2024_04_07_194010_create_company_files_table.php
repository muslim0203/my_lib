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
        Schema::create('company_files', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('file_id');
            $table->string('file_name');
            $table->string('title_oz');
            $table->string('title_uz');
            $table->string('title_ru');
            $table->string('type');
            $table->boolean('enabled');
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('company');

            $table->foreign('file_id')
                ->references('id')
                ->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_files', function (Blueprint $table) {
            $table->dropForeign(['file_id']);

            $table->dropForeign(['company_id']);
        });

        Schema::dropIfExists('company_files');
    }
};
