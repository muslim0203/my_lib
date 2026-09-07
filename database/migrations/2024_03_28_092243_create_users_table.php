<?php

use App\Core\Enums\Auth\LoginTypeEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->enum('login_type', LoginTypeEnum::getList())->default(LoginTypeEnum::_LOGIN_LOGIN_PASS->value);
            $table->integer('merchant_id')->nullable();
            $table->integer('social_user_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('merchant_id')
                ->references('id')
                ->on('merchants');

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees');

            $table->foreign('social_user_id')
                ->references('id')
                ->on('social_users');

            $table->index(['username']);
            $table->index(['email']);
            $table->index(['phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['phone']);
            $table->dropIndex(['email']);
            $table->dropIndex(['username']);

            $table->dropForeign(['merchant_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['social_user_id']);
        });

        Schema::dropIfExists('users');
    }
};
