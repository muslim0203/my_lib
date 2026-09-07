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
        Schema::table('link_authority_activity_spheres', function (Blueprint $table) {
            $table->dropPrimary(['authority_id']);
            $table->primary(['authority_id', 'sphere_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_authority_activity_spheres', function (Blueprint $table) {
            $table->dropPrimary(['authority_id', 'sphere_id']);
            $table->primary(['authority_id']);
        });
    }
};
