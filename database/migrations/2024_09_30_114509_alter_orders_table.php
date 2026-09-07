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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('transaction_create_time')->nullable();
            $table->string('transaction_perform_time')->nullable();
            $table->string('transaction_cancel_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('transaction_create_time');
            $table->dropColumn('transaction_perform_time');
            $table->dropColumn('transaction_cancel_time');
        });
    }
};
