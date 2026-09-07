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
            $table->bigInteger('request_id')
                ->nullable()
                ->index();

            $table->foreign('request_id')
                ->references('id')
                ->on('requests');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->bigInteger('request_id')
                ->nullable()
                ->index();

            $table->foreign('request_id')
                ->references('id')
                ->on('requests');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('request_id')
                ->nullable()
                ->index();

            $table->foreign('request_id')
                ->references('id')
                ->on('requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropIndex(['request_id']);
            $table->dropColumn('request_id');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropIndex(['request_id']);
            $table->dropColumn('request_id');
        });

        Schema::table('authority', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropIndex(['request_id']);
            $table->dropColumn('request_id');
        });
    }
};
