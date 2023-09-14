<?php

namespace Modules\Cms\Repositories\Services;

use Modules\Cms\Models\Service;
use App\Repositories\BaseEloquentRepository;

class ServiceRepository extends BaseEloquentRepository implements ServiceRepositoryInterface
{
    /** @var string */
    protected $modelName = Service::class;

    protected $filterableFields = ['category_id'];
    protected $searchableFields = ['title_ar', 'title_en'];
}
