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
        Schema::table('enum_categories', function (Blueprint $table) {
            $table->boolean('has_extra_column_require')
                ->default(false)
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enum_categories', function (Blueprint $table) {
            $table->dropIndex(['has_extra_column_require']);
            $table->dropColumn('has_extra_column_require');
        });
    }
};
