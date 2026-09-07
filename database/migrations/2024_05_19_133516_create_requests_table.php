<?php

use App\Core\Enums\Requests\RequestStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->integer('step_id');
            $table->string('model');
            $table->jsonb('data');
            $table->integer('author_id');
            $table->integer('confirm_author_id')->nullable();
            $table->integer('reject_author_id')->nullable();
            $table->enum('status', RequestStatusEnum::getList())->default(RequestStatusEnum::_CHECKING->value);
            $table->timestamps();

            $table->index('step_id');
            $table->index('model');
            $table->index('status');

            $table->foreign('step_id')->references('id')->on('process_steps')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('confirm_author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reject_author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['reject_author_id']);
            $table->dropForeign(['confirm_author_id']);
            $table->dropForeign(['author_id']);
            $table->dropForeign(['step_id']);

            $table->dropIndex(['status']);
            $table->dropIndex(['model']);
            $table->dropIndex(['step_id']);
        });

        Schema::dropIfExists('requests');
    }
};
