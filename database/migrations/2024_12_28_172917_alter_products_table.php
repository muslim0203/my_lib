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
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['description_oz', 'description_uz', 'description_ru']);
            $table->index(['description_oz']);
            $table->index(['description_uz']);
            $table->index(['description_ru']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['description_oz']);
            $table->dropIndex(['description_uz']);
            $table->dropIndex(['description_ru']);
            $table->index(['description_oz', 'description_uz', 'description_ru']);
        });
    }
};
