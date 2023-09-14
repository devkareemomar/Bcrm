<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'core_states';

    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
