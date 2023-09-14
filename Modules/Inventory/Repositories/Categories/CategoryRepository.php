<?php

namespace Modules\Inventory\Repositories\Categories;

use Modules\Inventory\Models\Category;
use App\Repositories\BaseEloquentRepository;

class CategoryRepository extends BaseEloquentRepository implements CategoryRepositoryInterface
{
    /** @var string */
    protected $modelName = Category::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
