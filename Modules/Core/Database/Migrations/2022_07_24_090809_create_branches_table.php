<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_branches', function (Blueprint $table) {
            $table->id();

            $table->string('name', 64);

            $table->text('address');
            $table->string('phone', 16);
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('commercial_register')->nullable();
            
            $table->foreignId('logo_media_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('header_media_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('footer_media_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('stamp_media_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('signature_media_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('document_media_id')->nullable()->constrained('core_media')->nullOnDelete();

            $table->foreignId('company_id')->constrained('core_companies')->cascadeOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('core_cities')->nullOnDelete();

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
        Schema::dropIfExists('core_branches');
    }
};
