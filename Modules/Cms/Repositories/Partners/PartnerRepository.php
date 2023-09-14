<?php

namespace Modules\Cms\Repositories\Partners;

use Modules\Cms\Models\Partner;
use App\Repositories\BaseEloquentRepository;

class PartnerRepository extends BaseEloquentRepository implements PartnerRepositoryInterface
{
    /** @var string */
    protected $modelName = Partner::class;

    protected $filterableFields = ['category_id'];
    protected $searchableFields = ['name'];
}
