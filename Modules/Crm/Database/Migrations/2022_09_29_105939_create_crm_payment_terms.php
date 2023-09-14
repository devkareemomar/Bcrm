<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Crm\Enums\PaymentTypeEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_payment_terms', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->date('date');
            $table->double('amount');
            $table->enum('payment_type', PaymentTypeEnum::values());
            $table->text('notes');

            $table->bigInteger('paymentable_id');
            $table->string('paymentable_type');

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
        Schema::dropIfExists('');
    }
};
