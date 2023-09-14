<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Cms\Enums\Posts\PostTypeEnum;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug_ar')->unique();
            $table->string('slug_en')->unique();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar');
            $table->text('description_en');
            $table->foreignId('category_id')->nullable()->constrained('cms_categories')->nullOnDelete();
            $table->foreignId('photo_media_id')->nullable()->constrained('cms_media')->nullOnDelete();
            $table->enum('type', PostTypeEnum::values());
            $table->string('author');
            // $table->dateTime('date');
            $table->tinyInteger('is_active');
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
        Schema::dropIfExists('cms_posts');
    }
}
