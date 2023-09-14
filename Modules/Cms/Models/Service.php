<?php

namespace Modules\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'cms_services';

    protected $guarded = [];


    public function category()
    {
        return $this->belongsTo(Category::class);
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

    public function icon()
    {
        return $this->belongsTo(Media::class, 'icon_media_id');
    }
}
