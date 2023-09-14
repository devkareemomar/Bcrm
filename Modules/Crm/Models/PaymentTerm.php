<?php

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $table = 'crm_payment_terms';

    protected $guarded = [];


    /**
     * Get the parent contactable models crm.
     */
    public function paymentable()
    {
        return $this->morphTo();
    }



}
