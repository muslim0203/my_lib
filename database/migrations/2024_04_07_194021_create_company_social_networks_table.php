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
        Schema::create('company_social_networks', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('name_oz');
            $table->string('name_uz');
            $table->string('name_ru');
            $table->string('icon_link');
            $table->string('link');
            $table->boolean('enabled');
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_social_networks', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::dropIfExists('company_social_networks');
    }
};
