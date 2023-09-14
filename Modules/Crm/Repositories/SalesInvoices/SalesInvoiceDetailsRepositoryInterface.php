<?php

namespace Modules\Crm\Repositories\SalesInvoices;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface SalesInvoiceDetailsRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function delete($id,$sales_invoice_id);
    public function deleteAll($ids,$sales_invoice_id);
}
