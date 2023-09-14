<?php

namespace Modules\Crm\Repositories\Quotations;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface QuotationDetailsRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function delete($id,$quotation_id);
    public function deleteAll($ids,$quotation_id);
}
