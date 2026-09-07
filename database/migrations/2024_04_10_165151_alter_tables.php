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
            $table->integer('step_id')->after('enabled');

            $table->foreign('step_id')
                ->references('id')
                ->on('process_steps');
        });

        Schema::table('process_steps', function (Blueprint $table) {
            $table->string('code_name')
                ->unique()
                ->after('enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('process_steps', function (Blueprint $table) {
            $table->dropColumn('code_name');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->dropForeign(['step_id']);

            $table->dropColumn('step_id');
        });
    }
};
