<?php

namespace Modules\Inventory\Models;

use Modules\Core\Models\Company;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'sto_categories';

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Self::class, 'parent_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
