<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoTransferItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sto_transfer_items', function (Blueprint $table) {
            $table->id();

            $table->float('quantity');

            $table->foreignId('item_id')->constrained('sto_items')->cascadeOnDelete();
            $table->foreignId('transfer_id')->constrained('sto_transfers')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('sto_units')->nullOnDelete();

            $table->unique(['item_id', 'transfer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sto_transfer_items');
    }
}
