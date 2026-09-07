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
        Schema::create('author_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id');
            $table->boolean('is_confirmed')->default(true);
            $table->text('description');
            $table->integer('created_by');
            $table->timestamps();

            $table->foreign('author_id')
                ->references('id')
                ->on('authors');

            $table->foreign('created_by')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('author_requests', function(Blueprint $table){
            $table->dropForeign(['created_by']);
            $table->dropForeign(['author_id']);
        });

        Schema::dropIfExists('author_requests');
    }
};
