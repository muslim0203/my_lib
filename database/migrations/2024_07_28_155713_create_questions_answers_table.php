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
        Schema::create('questions_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('question_id');
            $table->string('content_oz');
            $table->string('content_uz');
            $table->string('content_ru');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('question_id')
                ->references('id')
                ->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions_answers', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
        });

        Schema::dropIfExists('questions_answers');
    }
};
