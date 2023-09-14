<?php

namespace Modules\Crm\Http\Controllers\Api\Contacts;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Modules\Crm\Http\Requests\Contacts\ImportContactRequest;
use Modules\Crm\Repositories\Contacts\ContactRepositoryInterface;

class ContactDataManagementController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ContactRepositoryInterface $contactRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_contacts')->only('downloadExampleExcel', 'attributes', 'downloadExport');
        $this->middleware('permission:create_crm_contacts')->only('importData');
    }

    public function downloadExampleExcel()
    {
        return $this->contactRepository->downloadExampleExcel();
    }

    public function attributes()
    {
        $result = $this->contactRepository->getTableColumns(except: ['id', 'created_at', 'updated_at']);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function downloadExport(Request $request, $branchId)
    {
        return $this->contactRepository->downloadExport($request, $branchId);
    }

    public function importData(ImportContactRequest $request, $branchId)
    {
        return $this->contactRepository->importData($request, $branchId);
    }


}
