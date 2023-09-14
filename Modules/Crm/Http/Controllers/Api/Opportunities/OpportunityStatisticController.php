<?php

namespace Modules\Crm\Http\Controllers\Api\Opportunities;

use App\Http\Controllers\Api\ApiController;
use Modules\Crm\Repositories\Opportunities\OpportunityRepositoryInterface;

class OpportunityStatisticController extends ApiController
{
    /** inject required classes */
    public function __construct(protected OpportunityRepositoryInterface $opportyunityRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_opportunities')->only('statisticsStage', 'statisticsSource');
    }


    public  function statisticsStage()
    {
        $result = $this->opportyunityRepository->statisticsStage();

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public  function statisticsSource($branchId)
    {
        $result = $this->opportyunityRepository->statisticsSource($branchId);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }






}
