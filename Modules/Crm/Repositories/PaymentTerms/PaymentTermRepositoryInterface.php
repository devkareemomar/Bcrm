<?php

namespace Modules\Crm\Repositories\PaymentTerms;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface PaymentTermRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function bulkStore($data);
}
