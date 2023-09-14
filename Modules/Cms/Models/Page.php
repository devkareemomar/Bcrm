<?php

namespace Modules\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'cms_pages';

    protected $guarded = [];

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
