<?php

namespace Modules\Crm\Repositories\Opportunities;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface OpportunityRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function storeOpportunityDetails($items,$opportunity_id);
    public function maxId();
    public function statisticsStage();
    public function statisticsSource($branchId);
    public function downloadExport($request, $branchId);
}
