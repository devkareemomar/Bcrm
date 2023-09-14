<?php

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Branch;
use Modules\Core\Models\City;
use Modules\Core\Models\Country;
use Modules\Core\Models\Media;

class Contact extends Model
{
    protected $table = 'crm_contacts';

    protected $guarded = [];



    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function photo()
    {
        return $this->belongsTo(Media::class,'photo_id');
    }
}
