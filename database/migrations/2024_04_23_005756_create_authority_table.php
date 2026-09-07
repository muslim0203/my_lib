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
        Schema::create('authority', function (Blueprint $table) {
            $table->id();
            $table->string('name_oz');
            $table->string('name_uz');
            $table->string('name_ru');
            $table->integer('inn')->unique();
            $table->string('account', 50)->unique();
            $table->string('email', 50)->unique();
            $table->string('address', 50);
            $table->string('phone', 20);
            $table->integer('certificate_file_id');
            $table->string('certificate_file_name');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('certificate_file_id')
                ->references('id')
                ->on('files');

            $table->index(['name_oz', 'name_uz', 'name_ru']);
            $table->index(['inn']);
            $table->index(['email']);
            $table->index(['account']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority', function (Blueprint $table) {
            $table->dropForeign(['certificate_file_id']);

            $table->dropIndex(['name_oz', 'name_uz', 'name_ru']);
            $table->dropIndex(['inn']);
            $table->dropIndex(['email']);
            $table->dropIndex(['account']);
        });

        Schema::dropIfExists('authority');
    }
};
