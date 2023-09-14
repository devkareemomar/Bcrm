<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum PaymentableTypeEnum: string
{
    use EnumUtils;

    case OPPORTUNITY  = 'Opportunity';
    case QUOTATION    = 'Quotation';
    case SALESORDER   = 'SalesOrder';
    case SALESINVOICE = 'SalesInvoice';
}
