<?php

namespace Modules\Core\Repositories\Teams;

use Modules\Core\Models\TeamUser;
use App\Repositories\BaseEloquentRepository;

class TeamUserRepository extends BaseEloquentRepository implements TeamUserRepositoryInterface
{
    /** @var string */
    protected $modelName = TeamUser::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
