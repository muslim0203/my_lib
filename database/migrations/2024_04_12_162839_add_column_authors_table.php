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
        Schema::table('authors', function (Blueprint $table) {
            $table->integer('diploma_file_id');
            $table->string('diploma_file_name');
            $table->integer('licence_file_id');
            $table->string('licence_file_name');

            $table->foreign('diploma_file_id')
                ->references('id')
                ->on('files');

            $table->foreign('licence_file_id')
                ->references('id')
                ->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropForeign(['licence_file_id']);
            $table->dropForeign(['diploma_file_id']);

            $table->dropColumn('licence_file_id');
            $table->dropColumn('licence_file_name');
            $table->dropColumn('diploma_file_id');
            $table->dropColumn('diploma_file_name');
        });
    }
};
