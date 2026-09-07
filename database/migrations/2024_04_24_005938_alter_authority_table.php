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
            $table->integer('step_id');

            $table->index(['step_id']);

            $table->foreign('step_id')->references('id')->on('process_steps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority', function (Blueprint $table) {
            $table->dropForeign(['step_id']);

            $table->dropIndex(['step_id']);

            $table->dropColumn('step_id');
        });
    }
};
