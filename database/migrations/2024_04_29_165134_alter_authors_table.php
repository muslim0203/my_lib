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
        Schema::table('authors', function (Blueprint $table) {

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
        Schema::table('authors', function (Blueprint $table) {

            $table->dropIndex(['account_number']);
            $table->dropColumn('account_number');

            $table->string('account')->unique();
            $table->index(['account']);

        });
    }
};
