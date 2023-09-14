<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum ContactableTypeEnum: string
{
    use EnumUtils;

    case CLIENT = 'Client';
    case LEAD = 'Lead';
}
