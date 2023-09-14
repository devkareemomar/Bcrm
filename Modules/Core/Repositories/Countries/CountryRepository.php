<?php

namespace Modules\Core\Repositories\Countries;

use Modules\Core\Models\Country;
use App\Repositories\BaseEloquentRepository;

class CountryRepository extends BaseEloquentRepository implements CountryRepositoryInterface
{
    /** @var string */
    protected $modelName = Country::class;

    protected $filterableFields = [];
    protected $searchableFields = ['name'];
}
