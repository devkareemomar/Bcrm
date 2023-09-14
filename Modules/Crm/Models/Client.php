<?php

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Branch;
use Modules\Core\Models\CoreClass;
use Modules\Core\Models\Media;

class Client extends Model
{
    protected $table = 'crm_clients';

    protected $guarded = [];


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }



    public function activities()
    {
        return $this->morphMany(Activity::class, 'activitable');
    }



    public function documents()
    {
        return $this->belongsToMany(Media::class, 'crm_client_documents', 'client_id', 'document_media_id')->withPivot(['id', 'client_id', 'document_media_id', 'expire_date']);
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
