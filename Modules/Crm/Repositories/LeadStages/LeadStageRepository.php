<?php

namespace Modules\Crm\Repositories\LeadStages;

use Modules\Crm\Models\LeadStage;
use App\Repositories\BaseEloquentRepository;

class LeadStageRepository extends BaseEloquentRepository implements LeadStageRepositoryInterface
{
    /** @var string */
    protected $modelName = LeadStage::class;

    protected $filterableFields = [];
    protected $searchableFields = ['stage'];
}
