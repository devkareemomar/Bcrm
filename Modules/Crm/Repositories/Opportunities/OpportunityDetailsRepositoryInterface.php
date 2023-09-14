<?php

namespace Modules\Crm\Repositories\Opportunities;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface OpportunityDetailsRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function delete($id,$opportunity_id);
    public function deleteAll($ids,$opportunity_id);
}
