<?php

namespace Modules\Cms\Repositories\Products;

use Modules\Cms\Models\Product;
use App\Repositories\BaseEloquentRepository;

class ProductRepository extends BaseEloquentRepository implements ProductRepositoryInterface
{
    /** @var string */
    protected $modelName = Product::class;

    protected $filterableFields = ['category_id'];
    protected $searchableFields = ['name_ar', 'name_en'];
}
