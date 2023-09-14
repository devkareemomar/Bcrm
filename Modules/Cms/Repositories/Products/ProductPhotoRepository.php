<?php

namespace Modules\Cms\Repositories\Products;

use Modules\Cms\Models\ProductPhoto;
use App\Repositories\BaseEloquentRepository;

class ProductPhotoRepository extends BaseEloquentRepository implements ProductPhotoRepositoryInterface
{
    /** @var string */
    protected $modelName = ProductPhoto::class;
}
