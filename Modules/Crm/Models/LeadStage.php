<?php

namespace Modules\Crm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LeadStage extends Model
{
    protected $table = 'crm_lead_stages';

    protected $guarded = [];


    public function created_by()
    {
        return $this->belongsTo(User::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class,'stage_id');
    }
}
