<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum ActivitableTypeEnum: string
{
    use EnumUtils;

    case CLIENT = 'Client';
    case LEAD = 'Lead';

}
