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
        Schema::create('social_users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('current_address')->nullable();
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('file_id')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamps();

            $table->foreign('file_id')
                ->references('id')
                ->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_users', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
        });

        Schema::dropIfExists('social_users');
    }
};
