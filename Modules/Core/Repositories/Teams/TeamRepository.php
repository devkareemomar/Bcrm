<?php

namespace Modules\Core\Repositories\Teams;

use Modules\Core\Models\Team;
use App\Repositories\BaseEloquentRepository;

class TeamRepository extends BaseEloquentRepository implements TeamRepositoryInterface
{
    /** @var string */
    protected $modelName = Team::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
