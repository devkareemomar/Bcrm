<?php

namespace Modules\Cms\Repositories\Jobs;

use Modules\Cms\Models\Job;
use App\Repositories\BaseEloquentRepository;

class JobRepository extends BaseEloquentRepository implements JobRepositoryInterface
{
    /** @var string */
    protected $modelName = Job::class;

    protected $searchableFields = ['name_ar', 'name_en'];
}
