<?php

namespace Modules\Cms\Repositories\Categories;

use Modules\Cms\Models\Category;
use App\Repositories\BaseEloquentRepository;

class CategoryRepository extends BaseEloquentRepository implements CategoryRepositoryInterface
{
    /** @var string */
    protected $modelName = Category::class;

    protected $filterableFields = ['type'];
    protected $searchableFields = ['name_ar', 'name_en'];
}
