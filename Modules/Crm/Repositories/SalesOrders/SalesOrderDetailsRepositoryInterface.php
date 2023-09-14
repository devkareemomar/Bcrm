<?php

namespace Modules\Crm\Repositories\SalesOrders;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface SalesOrderDetailsRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function delete($id,$sales_order_id);
    public function deleteAll($ids,$sales_order_id);
}
