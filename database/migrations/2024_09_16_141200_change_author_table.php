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
        Schema::table('authors', function (Blueprint $table) {
            $table->integer('academic_degree_id')->nullable()->change();
            $table->integer('academic_position_id')->nullable()->change();
            $table->integer('education_type_id')->nullable()->change();
            $table->integer('profile_file_id')->nullable()->change();
            $table->integer('diploma_file_id')->nullable()->change();
            $table->string('diploma_file_name')->nullable()->change();
            $table->integer('licence_file_id')->nullable()->change();
            $table->string('licence_file_name')->nullable()->change();
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
