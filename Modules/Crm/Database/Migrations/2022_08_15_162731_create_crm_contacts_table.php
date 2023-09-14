<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->string('position')->nullable();



            $table->foreignId('client_id')->nullable()->constrained('crm_clients')->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('crm_leads')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('core_cities')->nullOnDelete();
            $table->foreignId('country_id')->nullable()->constrained('core_countries')->nullOnDelete();
            $table->foreignId('photo_id')->nullable()->constrained('core_media')->nullOnDelete();
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
        Schema::dropIfExists('crm_contacts');
    }
}
