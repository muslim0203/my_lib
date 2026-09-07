<?php

use App\Core\Enums\Genders\GenderEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->dropForeign(['merchant_id']);

            $table->dropColumn('merchant_id');
        });

        Schema::table('merchants', static function (Blueprint $table) {
            $table->dropForeign(['file_id']);
            $table->dropForeign(['education_type_id']);
        });

        Schema::dropIfExists('merchants');

        Schema::create('merchants', static function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('model_id');
            $table->string('model_type');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['user_id']);
            $table->index(['model_id', 'model_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchants', static function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['model_id', 'model_type']);
            $table->dropIndex(['user_id']);
        });

        Schema::dropIfExists('merchants');

        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->bigInteger('pin_fl')->unique();
            $table->text('description');
            $table->date('birth_date');
            $table->string('phone');
            $table->string('passport')->unique();
            $table->enum('gender', GenderEnum::getList())->default(GenderEnum::_MALE->value);
            $table->string('current_address');
            $table->integer('education_type_id');
            $table->integer('file_id')->nullable();
            $table->string('file_name')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('education_type_id')
                ->references('id')
                ->on('enum_education_types');

            $table->foreign('file_id')
                ->references('id')
                ->on('files');
        });

        Schema::table('users', static function (Blueprint $table) {
            $table->integer('merchant_id')
                ->nullable();

            $table->foreign('merchant_id')
                ->references('id')
                ->on('merchants');
        });

    }
};
