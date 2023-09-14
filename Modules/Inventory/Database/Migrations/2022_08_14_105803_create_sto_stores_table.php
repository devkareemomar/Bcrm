<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sto_stores', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();

            $table->foreignId('storekeeper_user_id')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('sto_stores');
    }
}
