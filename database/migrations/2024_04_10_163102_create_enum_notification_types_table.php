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
        Schema::create('enum_notification_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_oz');
            $table->string('name_uz');
            $table->string('name_ru');
            $table->string('code_name')->unique();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enum_notification_types');
    }
};
