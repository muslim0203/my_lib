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
        Schema::create('authority_files', function (Blueprint $table) {
            $table->id();
            $table->integer('authority_id');
            $table->integer('file_id');
            $table->string('file_name');
            $table->timestamps();

            $table->foreign('authority_id')->references('id')->on('authority')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority_files', function (Blueprint $table) {
            $table->dropForeign(['authority_id']);
        });

        Schema::dropIfExists('authority_files');
    }
};
