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
        Schema::create('user_interests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->string('model_type')->index();
            $table->integer('model_id')->index();
            $table->boolean('enabled')->default(true);
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_interests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->dropIndex(['model_type']);
            $table->dropIndex(['model_id']);
        });

        Schema::dropIfExists('user_interests');
    }
};
