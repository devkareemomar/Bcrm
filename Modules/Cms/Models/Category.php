<?php

namespace Modules\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'cms_categories';

    protected $guarded = [];

    public function partners()
    {
        return $this->hasMany(Partner::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class);
    }

    public function photo()
    {
        return $this->belongsTo(Media::class, 'photo_media_id');
    }
}
