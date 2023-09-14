<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum SalesOrderStageEnum: string
{
    use EnumUtils;

    case DRAFT = 'draft';
    case CREATED = 'created';
    case PARTIALLY = 'partially';
    case CANCEL = 'cancel';
    case FINISHED = 'finished';

}
