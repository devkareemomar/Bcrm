<?php

namespace Modules\Crm\Repositories\Contacts;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface ContactRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function maxId();
    public function downloadExampleExcel();
    public function downloadExport($request, $branchId);
    public function importData($request, $branchId);
}
