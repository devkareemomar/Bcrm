<?php

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Media;

class LeadDocument extends Model
{
    protected $table = 'crm_lead_documents';

    protected $guarded = [];



    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function document()
    {
        return $this->belongsTo(Media::class,'document_media_id');
    }

}
