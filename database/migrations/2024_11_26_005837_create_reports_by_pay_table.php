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
        Schema::create('report_by_pays', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('report_type_id');
            $table->integer('file_id');
            $table->string('file_name');
            $table->date('from_date')->index();
            $table->date('to_date')->index();
            $table->string('status')->default(MerchantStatusEnum::_NEW->value);
            $table->timestamps();

            $table
                ->foreign('report_type_id')
                ->references('id')
                ->on('report_types')
                ->onDelete('cascade');

            $table
                ->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_by_pays', function (Blueprint $table) {
            $table->dropForeign(['report_type_id']);
            $table->dropForeign(['file_id']);
            $table->dropIndex(['from_date']);
            $table->dropIndex(['to_date']);
        });

        Schema::dropIfExists('report_by_pays');
    }
};
