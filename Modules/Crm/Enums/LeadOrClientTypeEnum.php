<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum LeadOrClientTypeEnum: string
{
    use EnumUtils;

    case LEAD     = 'lead';
    case CLIENT = 'client';

}
