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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->date('birth_date');
            $table->bigInteger('pin_fl')->unique();
            $table->string('passport')->unique();
            $table->enum('gender', GenderEnum::getList())->default(GenderEnum::_MALE->value);
            $table->string('current_address');
            $table->integer('file_id')->nullable();
            $table->string('file_name')->nullable();
            $table->smallInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('file_id')
                ->references('id')
                ->on('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
        });

        Schema::dropIfExists('employees');
    }
};
