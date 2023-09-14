<?php

namespace Modules\Crm\Repositories\SalesInvoices;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface SalesInvoiceRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function storeSalesInvoiceDetails($items,$sales_invoice_id);
    public function maxId();
}
