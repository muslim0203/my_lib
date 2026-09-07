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
        Schema::create('main_banner_files', function (Blueprint $table) {
            $table->id();
            $table->integer('main_banner_id');
            $table->bigInteger('file_id');
            $table->timestamps();

            // index
            $table->index(['main_banner_id']);
            $table->index(['file_id']);

            //foreign

            $table->foreign('main_banner_id')
                ->references('id')
                ->on('main_banners');

            $table->foreign('file_id')
                ->references('id')
                ->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_banner_files',function (Blueprint $table){

            $table->dropIndex(['file_id']);
            $table->dropIndex(['main_banner_id']);

            $table->dropForeign(['file_id']);
            $table->dropForeign(['main_banner_id']);

        });

        Schema::dropIfExists('main_banner_files');
    }
};
