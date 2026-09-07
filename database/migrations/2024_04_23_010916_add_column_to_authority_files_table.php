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
        Schema::table('authority_files', function (Blueprint $table) {
            $table->integer('file_type_id');

            $table->foreign('file_type_id')->references('id')->on('enum_file_types');
        });

        Schema::table('link_author_files', function (Blueprint $table) {
            $table->integer('file_type_id');

            $table->foreign('file_type_id')->references('id')->on('enum_file_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_author_files', function (Blueprint $table) {
            $table->dropForeign(['file_type_id']);

            $table->dropColumn('file_type_id');
        });

        Schema::table('authority_files', function (Blueprint $table) {
            $table->dropForeign(['file_type_id']);

            $table->dropColumn('file_type_id');
        });
    }
};
