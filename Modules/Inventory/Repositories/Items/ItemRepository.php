<?php

namespace Modules\Inventory\Repositories\Items;

use Modules\Inventory\Models\Item;
use App\Repositories\BaseEloquentRepository;

class ItemRepository extends BaseEloquentRepository implements ItemRepositoryInterface
{
    /** @var string */
    protected $modelName = Item::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
