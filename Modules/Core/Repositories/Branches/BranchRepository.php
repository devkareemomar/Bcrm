<?php

namespace Modules\Core\Repositories\Branches;

use Modules\Core\Models\Branch;
use App\Repositories\BaseEloquentRepository;

class BranchRepository extends BaseEloquentRepository implements BranchRepositoryInterface
{
    /** @var string */
    protected $modelName = Branch::class;

    protected $filterableFields = [];
    protected $searchableFields = ['name'];
}
