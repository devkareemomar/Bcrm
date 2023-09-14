<?php

namespace Modules\Crm\Repositories\Leads;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface LeadRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function maxId();
    public function downloadExampleExcel();
    public function downloadExport($request, $branchId);
    public function importData($request, $branchId);
    public function statisticsStage($branchId);
    public function statisticsSource($branchId);
    public function statisticsLeadType($branchId);
    public function leadsByStage($branchId);
    public function convertToClient($branchId, $id);
    public function getLeadLogs($request);

}
