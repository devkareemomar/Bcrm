<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    protected $table = 'sto_quantities';

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
