<?php

namespace Modules\Inventory\Models;

use Modules\Core\Models\Media;
use Modules\Core\Models\Company;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'sto_items';

    protected $guarded = [];

    public function photo()
    {
        return $this->belongsTo(Media::class, 'photo_media_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
