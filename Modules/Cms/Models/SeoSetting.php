<?php

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{


    protected $table = 'cms_seo_settings';
    protected $fillable = [
        'key',
        'value',
    ];

}
