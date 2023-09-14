<?php

namespace Modules\Inventory\Repositories\Stores;

use Modules\Inventory\Models\Store;
use App\Repositories\BaseEloquentRepository;

class StoreRepository extends BaseEloquentRepository implements StoreRepositoryInterface
{
    /** @var string */
    protected $modelName = Store::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
