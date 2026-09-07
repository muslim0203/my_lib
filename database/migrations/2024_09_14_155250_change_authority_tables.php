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
        Schema::table('authority', static function (Blueprint $table) {
            $table->integer('activity_type_id')->index()->nullable();
            $table->foreign('activity_type_id')->references('id')->on('enum_activity_types');

            $table->integer('activity_sphere_id')->index()->nullable();
            $table->foreign('activity_sphere_id')->references('id')->on('enum_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority', static function (Blueprint $table) {
            $table->dropForeign(['activity_sphere_id']);
            $table->dropIndex(['activity_sphere_id']);
            $table->dropColumn('activity_sphere_id');

            $table->dropForeign(['activity_type_id']);
            $table->dropIndex(['activity_type_id']);
            $table->dropColumn('activity_type_id');
        });
    }
};
