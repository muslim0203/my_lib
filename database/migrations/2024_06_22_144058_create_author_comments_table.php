<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('author_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id');
            $table->string('comment');
            $table->integer('parent_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->index(['author_id']);
            $table->index(['user_id']);

            $table->foreign('author_id')
                ->references('id')
                ->on('authors');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('author_comments', function (Blueprint $table) {

            $table->dropIndex(['author_id']);
            $table->dropIndex(['user_id']);

            $table->dropForeign(['author_id']);
            $table->dropForeign(['user_id']);

        });

        Schema::dropIfExists('author_comments');
    }
};
