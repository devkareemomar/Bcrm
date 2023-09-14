<?php

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\Tax;
use Modules\Inventory\Models\Unit;

class SalesOrderDetails extends Model
{
    protected $table = 'crm_sales_order_details';

    protected $guarded = [];


    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

}
