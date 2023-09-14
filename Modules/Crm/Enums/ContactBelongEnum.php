<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum ContactBelongEnum: string
{
    use EnumUtils;

    case LEAD = 'lead';
    case CUSTOMER = 'customer';
    case SUPPLIER = 'supplier';

}
