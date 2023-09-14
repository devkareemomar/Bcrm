<?php

namespace Modules\Crm\Repositories\Quotations;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface QuotationRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function storeQuotationDetails($items,$opportunity_id);
    public function statisticsStage($branchId);
    public function downloadExport($request, $branchId);
    public function maxId();
}
