<?php

namespace Modules\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'cms_partners';

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

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_media_id');
    }
}
