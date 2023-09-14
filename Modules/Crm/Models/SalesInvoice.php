<?php

namespace Modules\Crm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Branch;
use Modules\Core\Models\Currency;
use Modules\Core\Models\Department;
use Modules\Core\Models\Media;

class SalesInvoice extends Model
{
    protected $table = 'crm_sales_invoices';

    protected $guarded = [];


    public function salesInvoiceDetails(){
        return $this->hasMany(SalesInvoiceDetails::class, 'sales_invoice_id');
    }


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function document()
    {
        return $this->belongsTo(Media::class,'document_media_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function approved_by()
    {
        return $this->belongsTo(Media::class,'approved_by_id');
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }






}
