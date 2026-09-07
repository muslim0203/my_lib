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
        Schema::create('main_banners', function (Blueprint $table) {
            $table->id();
            $table->text('name_uz');
            $table->text('name_ru');
            $table->text('name_oz');
            $table->text('content_uz')->nullable();
            $table->text('content_ru')->nullable();
            $table->text('content_oz')->nullable();
            $table->text('link')->nullable();
            $table->smallInteger('status')->default(1);
            $table->boolean('enabled')->default(true);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            // index
            $table->index(['created_by']);
            $table->index(['updated_by']);

            //foreign

            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_banners',function (Blueprint $table){

            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);

            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

        });

        Schema::dropIfExists('main_banners');
    }
};
