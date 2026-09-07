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
        Schema::create('link_author_files', function (Blueprint $table) {
            $table->integer('author_id');
            $table->integer('file_id');
            $table->string('file_name');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('author_id')
                ->references('id')
                ->on('authors');

            $table->foreign('file_id')
                ->references('id')
                ->on('files');

            $table->primary(['author_id', 'file_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_author_files', function (Blueprint $table) {
            $table->dropPrimary(['author_id', 'file_id']);
            $table->dropForeign(['file_id']);
            $table->dropForeign(['author_id']);
        });

        Schema::dropIfExists('link_author_files');
    }
};
