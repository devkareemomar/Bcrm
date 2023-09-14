<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'core_countries';

    protected $guarded = [];

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
