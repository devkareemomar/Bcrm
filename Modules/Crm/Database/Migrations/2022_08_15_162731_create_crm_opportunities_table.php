<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Crm\Enums\OpportunityStageEnum;
use Modules\Crm\Enums\PaymentTypeEnum;

class CreateCrmOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_opportunities', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('subject');
            $table->date('date');
            $table->double('sub_total');
            $table->double('total_tax');
            $table->double('total_discount');
            $table->double('total_cost');
            $table->enum('stage', OpportunityStageEnum::values());
            $table->enum('payment_type',PaymentTypeEnum::values());

            $table->integer('probability')->nullable();
            $table->text('description')->nullable();
            $table->text('terms')->nullable();

            $table->foreignId('document_media_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('source_id')->nullable()->constrained('crm_sources')->nullOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained('core_currencies')->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('crm_leads')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('core_departments')->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('core_teams')->nullOnDelete();
            $table->foreignId('assign_to_id')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('crm_opportunities');
    }
}
