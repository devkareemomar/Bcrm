<?php

namespace Modules\Core\Repositories\Departments;

use Modules\Core\Models\Department;
use App\Repositories\BaseEloquentBranchRepository;

class DepartmentRepository extends BaseEloquentBranchRepository implements DepartmentRepositoryInterface
{
    /** @var string */
    protected $modelName = Department::class;

    protected $filterableFields = [];
    protected $searchableFields = ['name'];
}
