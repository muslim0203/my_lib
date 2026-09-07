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
        Schema::table('product_discounts', function (Blueprint $table) {
            $table->date('from_expire_at')->nullable()->change();
            $table->date('to_expire_at')->nullable()->change();
            $table->boolean('enabled')->default(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_discounts', function (Blueprint $table) {
            $table->date('from_expire_at')->change();
            $table->date('to_expire_at')->change();
        });
    }
};
