<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'core_cities';

    protected $guarded = [];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
