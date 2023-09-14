<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Crm\Enums\LeadClassEnum;
use Modules\Crm\Enums\LeadOrClientTypeEnum;
use Modules\Crm\Enums\LeadTypeEnum;

class CreateCrmLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('company_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('mobile')->nullable();
            $table->string('email');
            $table->string('address')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('industry')->nullable();
            $table->text('details')->nullable();
            $table->text('longitude')->nullable();
            $table->text('latitude')->nullable();
            $table->text('number_of_employees')->nullable();
            $table->boolean('is_client')->default(0);
            $table->enum('type',LeadTypeEnum::values());

            $table->foreignId('photo_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('branch_id')->constrained('core_branches')->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('core_teams')->nullOnDelete();
            $table->foreignId('stage_id')->nullable()->constrained('crm_lead_stages')->nullOnDelete();
            $table->foreignId('source_id')->nullable()->constrained('crm_sources')->nullOnDelete();
            $table->foreignId('class_id')->constrained('core_classes')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('core_departments')->nullOnDelete();
            $table->foreignId('assign_to_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('order_by')->nullable();

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
        Schema::dropIfExists('crm_leads');
    }
}
