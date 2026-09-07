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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->enum('gender', GenderEnum::getList())->default(GenderEnum::_MALE->value);
            $table->string('passport');
            $table->bigInteger('pin_fl');
            $table->string('email', 50);
            $table->string('phone', 50);
            $table->integer('academic_degree_id');
            $table->integer('academic_position_id');
            $table->integer('education_type_id');
            $table->text('description');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('academic_degree_id')
                ->references('id')
                ->on('enum_academic_degrees');

            $table->foreign('academic_position_id')
                ->references('id')
                ->on('enum_academic_positions');

            $table->foreign('education_type_id')
                ->references('id')
                ->on('enum_education_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropForeign(['education_type_id']);
            $table->dropForeign(['academic_position_id']);
            $table->dropForeign(['academic_degree_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('authors');
    }
};
