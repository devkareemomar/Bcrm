<?php

namespace Modules\Core\Repositories\Currencies;

use Modules\Core\Models\Currency;
use App\Repositories\BaseEloquentRepository;

class CurrencyRepository extends BaseEloquentRepository implements CurrencyRepositoryInterface
{
    /** @var string */
    protected $modelName = Currency::class;

    protected $filterableFields = [];
    protected $searchableFields = [];
}
