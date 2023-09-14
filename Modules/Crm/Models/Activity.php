<?php

namespace Modules\Crm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Branch;
use Modules\Core\Models\Media;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Activity extends Model
{
    use LogsActivity;

    protected $table = 'crm_activities';

    protected $guarded = [];



    // activity log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'code','date','reminder_date','type','description','activitable_id','activitable_type',
                'created_by_id','assign_to_id','branch_id','created_at','updated_at'])
            ->useLogName('Lead')
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} Activity")
            ->dontSubmitEmptyLogs();
    }


    /**
     * Get the parent activitable models crm.
     */
    public function activitable()
    {
        return $this->morphTo();
    }

    public function document()
    {
        return $this->belongsTo(Media::class,'document_media_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by_id');
    }

    public function assignTo()
    {
        return $this->belongsTo(User::class,'assign_to_id');
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


}
