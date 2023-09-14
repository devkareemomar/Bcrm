<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sto_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->foreignId('parent_id')->nullable()->constrained('sto_categories')->nullOnDelete();
            $table->foreignId('company_id')->constrained('core_companies')->cascadeOnDelete();

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
        Schema::dropIfExists('sto_categories');
    }
}
