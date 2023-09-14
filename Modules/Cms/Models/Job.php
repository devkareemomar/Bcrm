<?php

namespace Modules\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'cms_jobs';

    protected $guarded = [];

    public function photo()
    {
        return $this->belongsTo(Media::class, 'photo_media_id');
    }
}
