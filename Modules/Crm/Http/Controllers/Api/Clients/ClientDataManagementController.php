<?php

namespace Modules\Crm\Http\Controllers\Api\Clients;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Contracts\Support\Renderable;
use Modules\Crm\Http\Requests\Clients\ImportClientRequest;
use Modules\Crm\Repositories\Clients\ClientRepositoryInterface;

class ClientDataManagementController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ClientRepositoryInterface $clientRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_clients')->only('downloadExampleExcel', 'attributes', 'downloadExport');
        $this->middleware('permission:create_crm_clients')->only('importData');
    }

    public function downloadExampleExcel()
    {
        return $this->clientRepository->downloadExampleExcel();
    }

    public function attributes()
    {
        $result = $this->clientRepository->getTableColumns(except: ['id', 'created_at', 'updated_at']);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function downloadExport(Request $request, $branchId)
    {
        return $this->clientRepository->downloadExport($request, $branchId);
    }

    public function importData(ImportClientRequest $request, $branchId)
    {
        return $this->clientRepository->importData($request, $branchId);
    }
}
