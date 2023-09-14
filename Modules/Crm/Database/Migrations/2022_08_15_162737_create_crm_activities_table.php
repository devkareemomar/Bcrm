<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Crm\Enums\ActivitTypeEnum;

class CreateCrmActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_activities', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->dateTime('date');
            $table->dateTime('reminder_date');
            $table->enum('type', ActivitTypeEnum::values());
            $table->text('description')->nullable();

            $table->bigInteger('activitable_id');
            $table->string('activitable_type');

            $table->foreignId('document_media_id')->nullable()->constrained('core_media')->nullOnDelete();

            $table->foreignId('created_by_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assign_to_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('branch_id')->constrained('core_branches')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_activities');
    }
}
