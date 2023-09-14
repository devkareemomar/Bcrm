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
        Schema::table('cms_solutions', function (Blueprint $table) {
            $table->string('meta_title_ar')->nullable()->after('photo_media_id');
            $table->string('meta_title_en')->nullable()->after('meta_title_ar');
            $table->string('meta_keyword_ar')->nullable()->after('meta_title_en');
            $table->string('meta_keyword_en')->nullable()->after('meta_keyword_ar');
            $table->text('meta_description_ar')->nullable()->after('meta_keyword_en');
            $table->text('meta_description_en')->nullable()->after('meta_description_ar');
            $table->text('custom_code_head')->nullable()->after('meta_description_en');
            $table->string('og_type')->nullable()->after('custom_code_head');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_solutions', function (Blueprint $table) {

        });
    }
};
