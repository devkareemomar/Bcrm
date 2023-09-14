<?php

namespace Modules\Core\Repositories\Companies;

use Modules\Core\Models\Company;
use App\Repositories\BaseEloquentRepository;

class CompanyRepository extends BaseEloquentRepository implements CompanyRepositoryInterface
{
    /** @var string */
    protected $modelName = Company::class;

    protected $filterableFields = [];
    protected $searchableFields = ['name'];
}
