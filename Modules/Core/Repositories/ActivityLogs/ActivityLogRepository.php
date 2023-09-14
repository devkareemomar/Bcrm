<?php

namespace Modules\Core\Repositories\ActivityLogs;

use App\Repositories\BaseEloquentRepository;
use Spatie\Activitylog\Models\Activity;

class ActivityLogRepository extends BaseEloquentRepository implements ActivityLogRepositoryInterface
{
    /** @var string */
    protected $modelName = Activity::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
