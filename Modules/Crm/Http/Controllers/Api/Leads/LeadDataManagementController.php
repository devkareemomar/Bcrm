<?php

namespace Modules\Crm\Http\Controllers\Api\Leads;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Modules\Crm\Http\Requests\Leads\ImportLeadRequest;
use Modules\Crm\Repositories\Leads\LeadRepositoryInterface;

class LeadDataManagementController extends ApiController
{
    /** inject required classes */
    public function __construct(protected LeadRepositoryInterface $leadRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_leads')->only('downloadExampleExcel', 'attributes', 'downloadExport');
        $this->middleware('permission:create_crm_leads')->only('importData');
    }

    public function downloadExampleExcel()
    {
        return $this->leadRepository->downloadExampleExcel();
    }

    public function attributes()
    {
        $result = $this->leadRepository->getTableColumns(except: ['id', 'created_at', 'updated_at']);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function downloadExport(Request $request, $branchId)
    {
        return $this->leadRepository->downloadExport($request, $branchId);
    }

    public function importData(ImportLeadRequest $request, $branchId)
    {
        return $this->leadRepository->importData($request, $branchId);
    }


    public function convertToClient($branchId, $id)
    {
        return $this->leadRepository->convertToClient($branchId, $id);

    }
}
