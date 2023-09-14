<?php


namespace Modules\Cms\Enums\Categories;

use App\Enums\EnumUtils;

enum CategoryTypeEnum: string
{
    use EnumUtils;

    case SERVICES     = 'services';
    case POSTS        = 'posts';
    case PARTENERS    = 'partners';
    case GALLERY      = 'galleries';
    case PRODUCTS     = 'products';
    case SLIDERS      = 'sliders';
    case TESTIMONIALS = 'testimonials';
}
