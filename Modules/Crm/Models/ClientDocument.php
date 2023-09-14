<?php

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Media;

class ClientDocument extends Model
{
    protected $table = 'crm_client_documents';

    protected $guarded = [];



    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function document()
    {
        return $this->belongsTo(Media::class,'document_media_id');
    }

}
