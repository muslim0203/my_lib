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
        Schema::create('users_verify_mail_tokens', function (Blueprint $table) {
            $table->integer('user_id')->unique();
            $table->string('token', 25);
            $table->timestamp('expire_at');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->index(['token']);

            $table->index(['expire_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_verify_mail_tokens', function(Blueprint $table){
            $table->dropIndex(['expire_at']);
            $table->dropIndex(['token']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('users_verify_mail_tokens');
    }
};
