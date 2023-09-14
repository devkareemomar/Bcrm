<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'core_companies';

    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_media_id');
    }

    public function header()
    {
        return $this->belongsTo(Media::class, 'header_media_id');
    }

    public function footer()
    {
        return $this->belongsTo(Media::class, 'footer_media_id');
    }

    public function stamp()
    {
        return $this->belongsTo(Media::class, 'stamp_media_id');
    }

    public function signature()
    {
        return $this->belongsTo(Media::class, 'signature_media_id');
    }

    public function document()
    {
        return $this->belongsTo(Media::class, 'document_media_id');
    }
}
