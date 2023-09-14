<?php

namespace Modules\Inventory\Repositories\Units;

use Modules\Inventory\Models\Unit;
use App\Repositories\BaseEloquentRepository;

class UnitRepository extends BaseEloquentRepository implements UnitRepositoryInterface
{
    /** @var string */
    protected $modelName = Unit::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
