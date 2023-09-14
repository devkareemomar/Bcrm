<?php

namespace Modules\Core\Repositories\Branches;

use Modules\Core\Models\BranchUser;
use App\Repositories\BaseEloquentRepository;

class BranchUserRepository extends BaseEloquentRepository implements BranchUserRepositoryInterface
{
    /** @var string */
    protected $modelName = BranchUser::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
