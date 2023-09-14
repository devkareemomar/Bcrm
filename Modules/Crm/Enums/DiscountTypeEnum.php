<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum DiscountTypeEnum: string
{
    use EnumUtils;

    case PERCENTAGE = 'percentage';
    case FLAT = 'flat';

}
