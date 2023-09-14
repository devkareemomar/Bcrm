<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Crm\Enums\DiscountTypeEnum;

class CreateCrmOpportunityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_opportunity_details', function (Blueprint $table) {
            $table->id();

            $table->double('quantity');
            $table->double('price');
            $table->double('total');
            $table->double('tax_rate')->nullable();
            $table->double('tax_value')->nullable();
            $table->double('discount_value')->nullable();
            $table->enum('discount_type', DiscountTypeEnum::values())->nullable();

            $table->foreignId('unit_id')->nullable()->constrained('sto_units')->nullOnDelete();
            $table->foreignId('tax_id')->nullable()->constrained('sto_taxes')->nullOnDelete();
            $table->foreignId('item_id')->constrained('sto_items')->cascadeOnDelete();
            $table->foreignId('opportunity_id')->constrained('crm_opportunities')->cascadeOnDelete();

            $table->unique(['opportunity_id','item_id']);

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
        Schema::dropIfExists('crm_opportunity_details');
    }
}
