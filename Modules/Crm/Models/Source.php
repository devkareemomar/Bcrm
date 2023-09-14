<?php

namespace Modules\Crm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'crm_sources';

    protected $guarded = [];

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class,'source_id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'source_id');
    }

    public function opportunity()
    {
        return $this->hasMany(Opportunity::class, 'source_id');
    }

}
