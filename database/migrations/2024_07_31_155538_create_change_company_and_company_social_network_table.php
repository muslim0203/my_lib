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
        Schema::table('company', function (Blueprint $table) {
            $table->text('title_oz')->change();
            $table->text('title_uz')->change();
            $table->text('title_ru')->change();
            $table->text('content_oz')->change();
            $table->text('content_uz')->change();
            $table->text('content_ru')->change();
        });

        Schema::table('company_partners', function (Blueprint $table) {
            $table->dropColumn('icon_link');
            $table->integer('logo_id')->nullable();

            $table->index(['logo_id']);

            $table->foreign('logo_id')
                ->references('id')
                ->on('files');

        });

        Schema::table('company_social_networks', function (Blueprint $table) {
            $table->dropColumn('icon_link');
            $table->integer('logo_id')->nullable();

            $table->index(['logo_id']);

            $table->foreign('logo_id')
                ->references('id')
                ->on('files');

        });

        Schema::table('questions', function (Blueprint $table) {
            $table->text('title_oz')->change();
            $table->text('title_uz')->change();
            $table->text('title_ru')->change();
        });

        Schema::table('questions_answers', function (Blueprint $table) {
            $table->text('content_oz')->change();
            $table->text('content_uz')->change();
            $table->text('content_ru')->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('title_oz')->change();
            $table->string('title_uz')->change();
            $table->string('title_ru')->change();
        });

        Schema::table('questions_answers', function (Blueprint $table) {
            $table->string('content_oz')->change();
            $table->string('content_uz')->change();
            $table->string('content_ru')->change();
        });

        Schema::table('company', function (Blueprint $table) {
            $table->string('title_oz')->change()->nullable();
            $table->string('title_uz')->change()->nullable();
            $table->string('title_ru')->change()->nullable();
            $table->string('content_oz')->change()->nullable();
            $table->string('content_uz')->change()->nullable();
            $table->string('content_ru')->change()->nullable();
        });

        Schema::table('company_partners', function (Blueprint $table) {

            $table->addColumn('string','icon_link')->nullable();

            $table->dropIndex(['logo_id']);
            $table->dropForeign(['logo_id']);
            $table->dropColumn('logo_id');

        });

        Schema::table('company_social_networks', function (Blueprint $table) {

            $table->addColumn('string','icon_link')->nullable();

            $table->dropIndex(['logo_id']);
            $table->dropForeign(['logo_id']);
            $table->dropColumn('logo_id');

        });
    }
};
