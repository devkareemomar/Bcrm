<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum ActivitTypeEnum: string
{
    use EnumUtils;

    case MEETING = 'meeting';
    case CALL = 'call';
    case EMAIL = 'email';

}
