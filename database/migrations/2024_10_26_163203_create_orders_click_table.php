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
        Schema::create('click_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->index();
            $table->integer('user_id')->index();
            $table->decimal('amount');
            $table->integer('service_id');
            $table->bigInteger('click_trans_id')->nullable();
            $table->bigInteger('click_paydoc_id')->nullable();
            $table->integer('action')->nullable();
            $table->integer('error')->nullable();
            $table->string('error_note')->nullable();
            $table->date('sign_time')->nullable();
            $table->string('sign_string')->nullable();
            $table->integer('merchant_confirm_id')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('click_payments');
    }
};
