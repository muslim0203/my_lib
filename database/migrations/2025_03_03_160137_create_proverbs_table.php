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
        Schema::create('proverbs', function (Blueprint $table) {
            $table->id();
            $table->text('author_oz');
            $table->text('author_uz');
            $table->text('author_ru');
            $table->text('content_oz');
            $table->text('content_uz');
            $table->text('content_ru');
            $table->boolean('enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proverbs');
    }
};
