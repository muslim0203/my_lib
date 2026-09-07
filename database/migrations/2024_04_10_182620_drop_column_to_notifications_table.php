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
        Schema::table('enum_notification_messages', function (Blueprint $table) {
            $table->dropColumn('code_name');
        });

        Schema::table('enum_notification_types', function (Blueprint $table) {
            $table->dropColumn('code_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enum_notification_types', function (Blueprint $table) {
            $table->string('code_name')->nullable();
        });

        Schema::table('enum_notification_messages', function (Blueprint $table) {
            $table->string('code_name')->nullable();
        });
    }
};
