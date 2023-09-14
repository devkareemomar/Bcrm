<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Inventory\Enums\TransferStatusEnum;

class CreateStoTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sto_transfers', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->dateTime('date');
            $table->enum('status',TransferStatusEnum::values());
            $table->float('total_quantity');
            $table->integer('items_count');
            $table->text('notes')->nullable();

            $table->foreignId('document_media_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('from_store_id')->constrained('sto_stores')->cascadeOnDelete();
            $table->foreignId('to_store_id')->constrained('sto_stores')->cascadeOnDelete();

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
        Schema::dropIfExists('sto_transfers');
    }
}
