<?php

namespace Modules\Crm\Repositories\PaymentTerms;

use Modules\Crm\Models\PaymentTerm;
use App\Repositories\BaseEloquentRepository;

class PaymentTermRepository extends BaseEloquentRepository implements PaymentTermRepositoryInterface
{
    /** @var string */
    protected $modelName = PaymentTerm::class;

    protected $filterableFields = [];
    protected $searchableFields = [];

    public function bulkStore($data)
    {
        $data = collect($data)->map(function ($row, $key) {
            $row['paymentable_type'] = 'Modules\Crm\Models\\' .$row['paymentable_type'];
            return $row;
        })->toArray();

        $this->insert($data);
    }

}
