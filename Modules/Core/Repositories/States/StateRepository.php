<?php

namespace Modules\Core\Repositories\States;

use Modules\Core\Models\State;
use App\Repositories\BaseEloquentRepository;

class StateRepository extends BaseEloquentRepository implements StateRepositoryInterface
{
    /** @var string */
    protected $modelName = State::class;

    protected $filterableFields = [];
    protected $searchableFields = ['name'];
}
