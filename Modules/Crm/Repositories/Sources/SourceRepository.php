<?php

namespace Modules\Crm\Repositories\Sources;

use Modules\Crm\Models\Source;
use App\Repositories\BaseEloquentRepository;

class SourceRepository extends BaseEloquentRepository implements SourceRepositoryInterface
{
    /** @var string */
    protected $modelName = Source::class;

    protected $filterableFields = [];
    protected $searchableFields = ['source'];
}
