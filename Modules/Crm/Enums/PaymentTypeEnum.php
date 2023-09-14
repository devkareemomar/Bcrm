<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum PaymentTypeEnum: string
{
    use EnumUtils;

    case CASH = 'cash';
    case BANK = 'bank';
    case CHECK = 'check';
    case VISA = 'visa';
    case PAYMENT_GATEWAY = 'payment_gateway';
}
