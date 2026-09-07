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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('notification_type_id');
            $table->integer('notification_message_id');
            $table->string('model')->nullable();
            $table->string('apply_id')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('notification_type_id')
                ->references('id')
                ->on('enum_notification_types');

            $table->foreign('notification_message_id')
                ->references('id')
                ->on('enum_notification_messages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['notification_message_id']);
            $table->dropForeign(['notification_type_id']);
        });

        Schema::dropIfExists('notifications');
    }
};
