<?php


namespace Modules\Cms\Enums\Pages;

use App\Enums\EnumUtils;

enum PageTypeEnum: string
{
    use EnumUtils;

    case NORMAL = 'normal';
    case LANDING = 'landing';
}
