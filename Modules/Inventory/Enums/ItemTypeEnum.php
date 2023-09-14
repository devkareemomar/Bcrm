<?php


namespace Modules\Inventory\Enums;

use App\Enums\EnumUtils;

enum ItemTypeEnum: string
{
    use EnumUtils;

    case SERVICE = 'service';
    case PRODUCT = 'product';


}
