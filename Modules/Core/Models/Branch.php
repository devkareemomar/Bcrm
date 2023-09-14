<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Crm\Models\Client;

class Branch extends Model
{
    protected $table = 'core_branches';

    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
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

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
