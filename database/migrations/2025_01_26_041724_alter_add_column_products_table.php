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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('licence_file_id')
                ->nullable()
                ->index();

            $table->date('licence_date')
                ->index()
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['licence_file_id']);
            $table->dropIndex(['licence_date']);

            $table->dropColumn(['licence_file_id']);
            $table->dropColumn(['licence_date']);
        });
    }
};
