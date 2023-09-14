<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Crm\Enums\ClientClassEnum;
use Modules\Crm\Enums\ClientTypeEnum;

class CreateCrmClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_clients', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company_name')->nullable();
            $table->string('phone');
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('industry')->nullable();
            $table->text('longitude')->nullable();
            $table->text('latitude')->nullable();
            $table->string('account_size')->nullable();
            $table->enum('type',ClientTypeEnum::values());

            $table->foreignId('photo_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('branch_id')->constrained('core_branches')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('core_classes')->cascadeOnDelete();
            $table->foreignId('source_id')->nullable()->constrained('crm_sources')->nullOnDelete();
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
        Schema::dropIfExists('crm_clients');
    }
}
