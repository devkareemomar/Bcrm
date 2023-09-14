<?php

namespace Modules\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'cms_products';

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
    public function photos()
    {
        return $this->belongsToMany(Media::class, ProductPhoto::class);
    }
}
