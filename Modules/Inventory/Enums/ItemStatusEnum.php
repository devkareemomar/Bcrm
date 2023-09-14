<?php


namespace Modules\Inventory\Enums;

use App\Enums\EnumUtils;

enum ItemStatusEnum: string
{
    use EnumUtils;

    case BUY = 'buy';
    case SELL = 'sell';
    case BOTH = 'both';

}
