<?php

namespace Modules\Core\Repositories\Classes;

use Modules\Core\Models\CoreClass;
use App\Repositories\BaseEloquentRepository;

class ClassRepository extends BaseEloquentRepository implements ClassRepositoryInterface
{
    /** @var string */
    protected $modelName = CoreClass::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
