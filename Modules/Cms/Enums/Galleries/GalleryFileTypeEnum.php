<?php


namespace Modules\Cms\Enums\Galleries;

use App\Enums\EnumUtils;

enum GalleryFileTypeEnum: string
{
    use EnumUtils;

    case PHOTO = 'photo';
    case VIDEO = 'video';
}
