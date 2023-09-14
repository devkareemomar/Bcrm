<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('category_id')->nullable()->constrained('cms_categories')->nullOnDelete();
            $table->string('slug_ar')->unique();
            $table->string('slug_en')->unique();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->longText('content_ar')->nullable();
            $table->longText('content_en')->nullable();
            $table->foreignId('icon_media_id')->nullable()->constrained('cms_media')->nullOnDelete();
            $table->foreignId('photo_media_id')->nullable()->constrained('cms_media')->nullOnDelete();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('cms_services');
    }
}
