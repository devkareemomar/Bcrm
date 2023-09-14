<?php

namespace Modules\Cms\Repositories\Solutions;

use Modules\Cms\Models\Solution;
use App\Repositories\BaseEloquentRepository;

class SolutionRepository extends BaseEloquentRepository implements SolutionRepositoryInterface
{
    /** @var string */
    protected $modelName = Solution::class;

    protected $filterableFields = [];
    protected $searchableFields = ['title_ar', 'title_en'];

}
