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
            $table->integer('request_type_id');

            $table->index(['request_type_id']);

            $table->foreign('request_type_id')->references('id')->on('enum_request_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['request_type_id']);

            $table->dropIndex(['request_type_id']);

            $table->dropColumn('request_type_id');
        });
    }
};
