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
        Schema::table('requests', function (Blueprint $table) {
            $table->jsonb('changed_data')->nullable();
            $table->integer('model_id')->nullable();
            $table->string('comment')->nullable();

            $table->index(['model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropIndex(['model_id']);

            $table->dropColumn('comment');
            $table->dropColumn('model_id');
            $table->dropColumn('changed_data');
        });
    }
};
