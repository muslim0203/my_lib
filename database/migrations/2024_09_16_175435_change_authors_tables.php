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
        Schema::table('authors', function (Blueprint $table) {
            $table->date('passport_given_date')->index()->nullable();
            $table->string('passport_given_place')->index()->nullable();
            $table->string('inn', 9)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropIndex(['passport_given_place']);
            $table->dropIndex(['passport_given_date']);

            $table->dropColumn('passport_given_date');
            $table->dropColumn('passport_given_place');
            $table->dropColumn('inn');
        });
    }
};
