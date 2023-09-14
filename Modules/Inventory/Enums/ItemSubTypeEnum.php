<?php


namespace Modules\Inventory\Enums;

use App\Enums\EnumUtils;

enum ItemSubTypeEnum: string
{
    use EnumUtils;

    case DIGITAL = 'digital';
    case PHYSICAL = 'physical';
    case STANDARD = 'standard';


}
