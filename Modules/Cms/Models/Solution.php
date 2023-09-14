<?php

namespace Modules\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $table = 'cms_solutions';

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Solution::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Solution::class, 'parent_id');
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
