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
        Schema::table('author_requests', function (Blueprint $table) {

            $table->dropForeign(['author_id']);

            $table->string('model');
            $table->integer('model_id');

            $table->dropColumn('author_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('author_requests', function (Blueprint $table) {

            $table->integer('author_id')->nullable();

            $table->dropColumn('model_id');
            $table->dropColumn('model');

            $table->foreign('author_id')
                ->references('id')
                ->on('authors');

        });
    }
};
