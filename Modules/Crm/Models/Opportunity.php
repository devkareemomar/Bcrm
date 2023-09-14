<?php

namespace Modules\Crm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Branch;
use Modules\Core\Models\Currency;
use Modules\Core\Models\Department;
use Modules\Core\Models\Media;
use Modules\Core\Models\Team;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Opportunity extends Model
{
    use LogsActivity;

    protected $table = 'crm_opportunities';

    protected $guarded = [];


    // activity log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'code','subject','date','sub_total','total_tax','total_discount','total_cost','stage','payment_type','probability',
                'description','terms','document_media_id','source_id','currency_id','lead_id','department_id','team_id','assign_to_id','branch_id','created_at',
                'updated_at'])
            ->useLogName('Lead')
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} Opportunity")
            ->dontSubmitEmptyLogs();
    }

    public function opportunityDetails(){
        return $this->hasMany(OpportunityDetails::class, 'opportunity_id');
    }

    public function document()
    {
        return $this->belongsTo(Media::class,'document_media_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function assign_to()
    {
        return $this->belongsTo(User::class ,'assign_to_id');
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function paymentTerms()
    {
        return $this->morphMany(PaymentTerm::class, 'paymentable');
    }
}
