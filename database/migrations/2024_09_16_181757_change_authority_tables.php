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
        Schema::table('authority', function (Blueprint $table) {
            $table->integer('patent_file_id')->nullable()->index();
            $table->string('patent_file_name')->nullable()->index();

            $table->foreign('patent_file_id')
                ->references('id')
                ->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority', function (Blueprint $table) {
            $table->dropForeign(['patent_file_id']);
            $table->dropIndex(['patent_file_id']);
            $table->dropIndex(['patent_file_name']);

            $table->dropColumn('patent_file_id');
            $table->dropColumn('patent_file_name');
        });
    }
};
