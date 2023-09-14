<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum LeadTypeEnum: string
{
    use EnumUtils;

    case INDIVIDUAL = 'individual';
    case COMPANY    = 'company';

}
