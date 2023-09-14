<?php

namespace Modules\Crm\Http\Controllers\Api\SalesOrders;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Modules\Crm\Repositories\SalesOrders\SalesOrderRepositoryInterface;

class SalesOrderDataManagementController extends ApiController
{
    /** inject required classes */
    public function __construct(protected SalesOrderRepositoryInterface $salesorderRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_salesorders')->only('attributes', 'downloadExport');
    }



    public function attributes()
    {
        $result = $this->salesorderRepository->getTableColumns(except: ['id', 'created_at', 'updated_at']);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function downloadExport(Request $request, $branchId)
    {
        try {

            return $this->salesorderRepository->downloadExport($request, $branchId);

        }catch (\Exception $e) {

            return   $this->jsonResponse()->setStatus(false)
                        ->setCode(500)
                        ->setResult($e->getMessage());
        }
    }
}
