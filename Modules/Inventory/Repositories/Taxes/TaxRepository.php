<?php

namespace Modules\Inventory\Repositories\Taxes;

use Modules\Inventory\Models\Tax;
use App\Repositories\BaseEloquentRepository;

class TaxRepository extends BaseEloquentRepository implements TaxRepositoryInterface
{
    /** @var string */
    protected $modelName = Tax::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
