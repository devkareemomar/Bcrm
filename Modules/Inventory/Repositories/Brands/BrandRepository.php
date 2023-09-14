<?php

namespace Modules\Inventory\Repositories\Brands;

use Modules\Inventory\Models\Brand;
use App\Repositories\BaseEloquentRepository;

class BrandRepository extends BaseEloquentRepository implements BrandRepositoryInterface
{
    /** @var string */
    protected $modelName = Brand::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
