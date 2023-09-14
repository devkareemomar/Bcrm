<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Inventory\Enums\ItemTypeEnum;
use Modules\Inventory\Enums\ItemStatusEnum;
use Modules\Inventory\Enums\ItemSubTypeEnum;
use Illuminate\Database\Migrations\Migration;

class CreateStoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sto_items', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('item_link')->nullable();
            $table->text('description')->nullable();


            $table->double('purchase_price');
            $table->double('sale_price');
            $table->double('min_price');

            $table->string('packing')->nullable();
            $table->string('packing_size')->nullable();
            $table->string('item_weight')->nullable();
            $table->integer('alert_quantity')->nullable();

            $table->boolean('is_active')->default(1);
            $table->enum('item_status',ItemStatusEnum::values());

            $table->enum('type',ItemTypeEnum::values());
            $table->enum('sub_type',ItemSubTypeEnum::values());

            $table->foreignId('photo_media_id')->nullable()->constrained('core_media')->nullOnDelete();
            $table->foreignId('tax_id')->nullable()->constrained('sto_taxes')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('sto_categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('sto_brands')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('sto_units')->nullOnDelete();
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
        Schema::dropIfExists('sto_items');
    }
}
