<?php

use App\Core\Enums\Reports\MerchantStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products_orders', function (Blueprint $table) {
            $table->string('merchant_status')->default(MerchantStatusEnum::_NEW->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_orders', function (Blueprint $table) {
            $table->dropColumn('merchant_status');
        });
    }
};
