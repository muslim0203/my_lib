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
        Schema::create('link_author_subscribers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('subscriber_id');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->index(['author_id']);
            $table->index(['subscriber_id']);

            $table->foreign('author_id')
                ->references('id')
                ->on('users');

            $table->foreign('subscriber_id')
                ->references('id')
                ->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_author_subscribers', function (Blueprint $table) {

            $table->dropForeign(['subscriber_id']);
            $table->dropForeign(['author_id']);

            $table->dropIndex(['subscriber_id']);
            $table->dropIndex(['author_id']);

        });

        Schema::dropIfExists('link_author_subscribers');
    }
};
