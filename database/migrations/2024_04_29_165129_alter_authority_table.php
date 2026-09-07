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

            $table->integer('user_id');
            $table->index(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');

            $table->dropIndex(['account']);
            $table->dropColumn('account');

            $table->string('account_number')->unique();
            $table->index(['account_number']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority', function (Blueprint $table) {

            $table->dropIndex(['account_number']);
            $table->dropColumn('account_number');

            $table->string('account')->nullable()->unique();
            $table->index(['account']);

            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');

        });
    }
};
