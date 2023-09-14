<?php


namespace Modules\Crm\Enums;

use App\Enums\EnumUtils;

enum ClientClassEnum: string
{
    use EnumUtils;

    case A = 'A';
    case B = 'C';
    case C = 'B';

}
