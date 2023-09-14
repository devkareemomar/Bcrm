<?php

namespace Modules\Crm\Http\Controllers\Api\Quotations;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Modules\Crm\Repositories\Quotations\QuotationRepositoryInterface;

class QuotationDataManagementController extends ApiController
{
    /** inject required classes */
    public function __construct(protected QuotationRepositoryInterface $quotationRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_quotations')->only('attributes', 'downloadExport');
    }



    public function attributes()
    {
        $result = $this->quotationRepository->getTableColumns(except: ['id', 'created_at', 'updated_at']);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function downloadExport(Request $request, $branchId)
    {
        try {

            return $this->quotationRepository->downloadExport($request, $branchId);

        }catch (\Exception $e) {

            return   $this->jsonResponse()->setStatus(false)
                        ->setCode(500)
                        ->setResult($e->getMessage());
        }
    }
}
