<?php

namespace Modules\Crm\Repositories\SalesOrders;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface SalesOrderRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function storeSalesOrderDetails($items,$sales_order_id);
    public function statisticsStage($branchId);
    public function downloadExport($request, $branchId);
    public function maxId();
}
