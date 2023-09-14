<?php

namespace Modules\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'cms_galleries';

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

    public function file()
    {
        return $this->belongsTo(Media::class, 'file_media_id');
    }
}
