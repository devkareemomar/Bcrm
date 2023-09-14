<?php


namespace Modules\Cms\Enums\Posts;

use App\Enums\EnumUtils;

enum PostTypeEnum: string
{
    use EnumUtils;

    case ARTICLE = 'article';
    case VIDEO = 'video';
}
