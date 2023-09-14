<?php

namespace Modules\Crm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Cms\Models\Faq;
use Modules\Core\Models\Branch;
use Modules\Core\Models\CoreClass;
use Modules\Core\Models\Department;
use Modules\Core\Models\Media;
use Modules\Core\Models\Team;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use LogsActivity;

    protected $table = 'crm_leads';

    protected $guarded = [];


      // activity log
      public function getActivitylogOptions(): LogOptions
      {
          return LogOptions::defaults()
              ->logOnly([
                'id','code','company_name','contact_person','first_name','last_name','phone','email','mobile','address','location',
                'city','country','website','facebook','twitter','linkedin',
                'industry','industry','details','longitude','latitude',
                'number_of_employees','is_client','type','photo_id','branch_id','team_id','stage_id','source_id','class_id',
                'department_id','assign_to_id','order_by','created_at','updated_at'])
                ->useLogName('Lead')
                ->setDescriptionForEvent(fn(string $eventName) => "{$eventName} Lead")
                ->dontSubmitEmptyLogs();
      }




    public function assign()
    {
        return $this->belongsTo(User::class, 'assign_to_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function stage()
    {
        return $this->belongsTo(LeadStage::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'activitable');
    }



    public function documents()
    {
        return $this->belongsToMany(Media::class, 'crm_lead_documents', 'lead_id', 'document_media_id')->withPivot(['id', 'lead_id', 'document_media_id', 'expire_date']);
    }
    public function photo()
    {
        return $this->belongsTo(Media::class, 'photo_id');
    }


    public function class()
    {
        return $this->belongsTo(CoreClass::class, 'class_id');
    }
}
