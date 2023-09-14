<?php

namespace Modules\Core\Repositories\Cities;

use Modules\Core\Models\City;
use App\Repositories\BaseEloquentRepository;

class CityRepository extends BaseEloquentRepository implements CityRepositoryInterface
{
    /** @var string */
    protected $modelName = City::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
