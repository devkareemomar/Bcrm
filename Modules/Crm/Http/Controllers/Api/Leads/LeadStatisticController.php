<?php

namespace Modules\Crm\Http\Controllers\Api\Leads;

use App\Http\Controllers\Api\ApiController;
use Modules\Crm\Http\Requests\Leads\BulkUpdateStageLeadRequest;
use Modules\Crm\Http\Resources\Leads\LeadResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\Leads\LeadRepositoryInterface;
use Modules\Crm\Http\Requests\Leads\UpdateStageLeadRequest;
use Modules\Crm\Http\Resources\Leads\StageLeadResource;

class LeadStatisticController extends ApiController
{
    /** inject required classes */
    public function __construct(protected LeadRepositoryInterface $leadRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_leads')->only('statisticsStage', 'statisticsSource', 'statisticsLeadType', 'leadsByStage');
        $this->middleware('permission:update_crm_leads')->only('updateStageLead');
    }


    public  function statisticsStage($branchId)
    {
        $result = $this->leadRepository->statisticsStage($branchId);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public  function statisticsSource($branchId)
    {
        $result = $this->leadRepository->statisticsSource($branchId);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public  function statisticsLeadType($branchId)
    {
        $result = $this->leadRepository->statisticsLeadType($branchId);
        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function leadsByStage($branchId)
    {
        $result = $this->leadRepository->leadsByStage($branchId);

        $result =  StageLeadResource::collection($result);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function updateStageLead(UpdateStageLeadRequest $request, $branchId, $id)
    {

        $data = $request->validated();

        $lead = $this->leadRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$lead) {
            throw new NotFoundHttpException();
        }

        $lead = $this->leadRepository->updateByInstance($lead, $data);

        $result = new LeadResource($lead);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("lead updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }

    public function bulkUpdateStageLead(BulkUpdateStageLeadRequest $request, $branchId)
    {
        $data = $request->validated();

        foreach ($data as  $value) {
            $lead = $this->leadRepository->findByMany(['id' => $value['id'], 'branch_id' => $branchId]);

            if (!$lead) {
                throw new NotFoundHttpException();
            }

            $lead = $this->leadRepository->updateByInstance($lead, $value);

        }

        // $result = new LeadResource($lead);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("lead updated successfully.")
            ->setCode(200);
            // ->setResult($result);
    }


}
