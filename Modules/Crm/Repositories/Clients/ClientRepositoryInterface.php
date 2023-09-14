<?php

namespace Modules\Crm\Repositories\Clients;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface ClientRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function maxId();
    public function downloadExampleExcel();
    public function downloadExport($request, $branchId);
    public function importData($request, $branchId);
    public function statisticsSource($branchId);
    public function statisticsClientType($branchId);
    public function statisticsClientByBranch($branchId);
}
