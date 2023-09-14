<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Crm\Enums\PaymentTypeEnum;
use Modules\Crm\Enums\QuotationStageEnum;

class CreateCrmQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_quotations', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('subject');
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->double('sub_total');
            $table->double('total_tax');
            $table->double('total_discount');
            $table->double('total_cost');

            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->enum('stage', QuotationStageEnum::values());
            $table->enum('payment_type',PaymentTypeEnum::values());


            $table->foreignId('team_id')->nullable()->constrained('core_teams')->nullOnDelete();
            $table->foreignId('assign_to_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained('core_currencies')->nullOnDelete();
            $table->foreignId('document_media_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('client_id')->constrained('crm_clients')->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('crm_contacts')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('core_departments')->nullOnDelete();
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
        Schema::dropIfExists('crm_quotations');
    }
}
