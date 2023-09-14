<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sto_quantities', function (Blueprint $table) {
            $table->id();

            $table->float('quantity');

            $table->foreignId('store_id')->constrained('sto_stores')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('sto_items')->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['store_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sto_quantities');
    }
}
