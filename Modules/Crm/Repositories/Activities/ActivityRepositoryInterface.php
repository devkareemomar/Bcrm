<?php

namespace Modules\Crm\Repositories\Activities;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface ActivityRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function maxId();
    public function getAllByDate($request, $branchId);
    public function bulkStore($data, $branchId);

}
