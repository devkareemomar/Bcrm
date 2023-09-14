<?php

namespace Modules\Crm\Http\Controllers\Api\Clients;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crm\Repositories\Clients\ClientRepositoryInterface;

class ClientStatisticController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ClientRepositoryInterface $clientRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_clients')->only('statisticsSource', 'statisticsClientType', 'statisticsClientByBranch');
    }

    public  function statisticsSource($branchId)
    {
        $result = $this->clientRepository->statisticsSource($branchId);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public  function statisticsClientType($branchId)
    {
        $result = $this->clientRepository->statisticsClientType($branchId);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function statisticsClientByBranch($branchId)
    {
        $result = $this->clientRepository->statisticsClientByBranch($branchId);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

}
