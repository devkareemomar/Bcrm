<?php

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\Tax;
use Modules\Inventory\Models\Unit;

class QuotationDetails extends Model
{
    protected $table = 'crm_quotation_details';

    protected $guarded = [];


    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
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
