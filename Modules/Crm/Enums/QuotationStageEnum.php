<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum QuotationStageEnum: string
{
    use EnumUtils;

    case DRAFT = 'draft';
    case CREATED = 'created';
    case SEND = 'send';
    case APPROVED = 'approved';
    case CANCEL = 'cancel';
    case WIN = 'win';

}
