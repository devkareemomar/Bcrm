<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_partners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('category_id')->nullable()->constrained('cms_categories')->nullOnDelete();
            $table->string('name');
            $table->string('title')->nullable();
            $table->text('opinion')->nullable();
            $table->tinyInteger('evaluation')->nullable();
            $table->foreignId('logo_media_id')->nullable()->constrained('cms_media')->nullOnDelete();
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
        Schema::dropIfExists('cms_partners');
    }
}
