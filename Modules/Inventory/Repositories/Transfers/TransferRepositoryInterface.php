<?php

namespace Modules\Inventory\Repositories\Transfers;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface TransferRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function maxId();
}
