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
        Schema::create('enum_notification_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('notification_type_id');
            $table->string('message_oz');
            $table->string('message_uz');
            $table->string('message_ru');
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
        Schema::dropIfExists('enum_notification_messages');
    }
};
