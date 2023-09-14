<?php

namespace Modules\Inventory\Models;

use Modules\Core\Models\Company;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'sto_brands';

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
