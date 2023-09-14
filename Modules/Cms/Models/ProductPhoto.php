<?php

namespace Modules\Cms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    protected $table = 'cms_product_photos';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
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
