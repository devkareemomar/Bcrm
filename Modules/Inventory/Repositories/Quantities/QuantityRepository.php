<?php

namespace Modules\Inventory\Repositories\Quantities;

use Modules\Inventory\Models\Quantity;
use App\Repositories\BaseEloquentRepository;

class QuantityRepository extends BaseEloquentRepository implements QuantityRepositoryInterface
{
    /** @var string */
    protected $modelName = Quantity::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
