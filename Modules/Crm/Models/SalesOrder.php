<?php

namespace Modules\Crm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Branch;
use Modules\Core\Models\Currency;
use Modules\Core\Models\Department;
use Modules\Core\Models\Media;
use Modules\Core\Models\Team;

class SalesOrder extends Model
{
    protected $table = 'crm_sales_orders';

    protected $guarded = [];


    public function salesOrderDetails(){
        return $this->hasMany(SalesOrderDetails::class, 'sales_order_id');
    }

    public function approved_by()
    {
        return $this->belongsTo(Media::class,'approved_by_id');
    }

    public function document()
    {
        return $this->belongsTo(Media::class,'document_media_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function assign_to()
    {
        return $this->belongsTo(User::class ,'assign_to_id');
    }


    public function paymentTerms()
    {
        return $this->morphMany(PaymentTerm::class, 'paymentable');
    }
}
