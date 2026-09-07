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
        Schema::create('link_authority_activity_spheres', function (Blueprint $table) {
            $table->integer('authority_id')->index()->primary();
            $table->foreign('authority_id')
                ->references('id')
                ->on('authority')
                ->onDelete('cascade');

            $table->integer('sphere_id')->index();
            $table->foreign('sphere_id')
                ->references('id')
                ->on('enum_categories');

        });

        Schema::table('authority', function (Blueprint $table) {
            $table->dropForeign(['activity_sphere_id']);
            $table->dropColumn('activity_sphere_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority', function (Blueprint $table) {
            $table->integer('activity_sphere_id')->nullable();
            $table->foreign('activity_sphere_id')
                ->references('id')
                ->on('enum_categories');
        });

        Schema::table('link_authority_activity_spheres', function (Blueprint $table) {
            $table->dropForeign(['authority_id']);

            $table->dropForeign(['sphere_id']);

        });

        Schema::dropIfExists('link_authority_activity_spheres');
    }
};
