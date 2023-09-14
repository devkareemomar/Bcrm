<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum ClientTypeEnum: string
{
    use EnumUtils;

    case INDIVIDUAL = 'individual';
    case COMPANY = 'company';

}
