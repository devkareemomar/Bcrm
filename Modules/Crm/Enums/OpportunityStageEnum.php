<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum OpportunityStageEnum: string
{
    use EnumUtils;

    case NEGOTIATION = 'negotiation';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case DELAYED = 'delayed';

}
