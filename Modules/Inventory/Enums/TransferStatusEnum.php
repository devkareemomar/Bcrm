<?php


namespace Modules\Inventory\Enums;

use App\Enums\EnumUtils;

enum TransferStatusEnum: string
{
    use EnumUtils;

    case DRAFT = 'draft';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case COMPLETED = 'completed';

}
