<?php

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\Tax;

class SalesInvoiceDetails extends Model
{
    protected $table = 'crm_sales_invoice_details';

    protected $guarded = [];


    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

}
