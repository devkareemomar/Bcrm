<?php

namespace Modules\Crm\Http\Controllers\Api\Leads;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Http\Request;
use Modules\Crm\Http\Resources\Leads\LeadResource;
use Modules\Crm\Http\Resources\Leads\LeadCollection;
use Modules\Crm\Http\Requests\Leads\StoreLeadRequest;
use Modules\Crm\Http\Requests\Leads\UpdateLeadRequest;
use Modules\Crm\Http\Resources\Leads\LeadBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\Leads\LeadRepositoryInterface;
use Modules\Core\Http\Resources\ActivityLogs\ActivityLogResource;
use Spatie\Activitylog\Models\Activity;

class LeadController extends ApiController
{
    /** inject required classes */
    public function __construct(protected LeadRepositoryInterface $leadRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_leads')->only('index', 'show');
        $this->middleware('permission:create_crm_leads')->only('store');
        $this->middleware('permission:update_crm_leads')->only('update');
        $this->middleware('permission:delete_crm_leads')->only('destroy', 'bulkDestroy');
    }


    public function index(Request $request, $branchId)
    {

        $leads = $this->leadRepository->paginate('id', ['assign'], paginate: $request->limit ?? 25, parameters: ['branch_id' => $branchId]);

        $result = new LeadCollection($leads);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($branchId)
    {
        $leads = $this->leadRepository->getAll(parameters: ['branch_id' => $branchId]);

        $result = LeadBriefResource::collection($leads);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreLeadRequest $request, $branchId)
    {

        $data = $request->validated();

        $lead = $this->leadRepository->store($data);

        $result = new LeadResource($lead);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("lead created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($branchId, $id)
    {
        $lead = $this->leadRepository->findByMany(['id' => $id, 'branch_id' => $branchId],['documents','team','photo','assign','department']);

        if (!$lead) {
            throw new NotFoundHttpException();
        }

        $result =  new LeadResource($lead);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateLeadRequest $request, $branchId, $id)
    {
        $lead = $this->leadRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$lead) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $lead = $this->leadRepository->updateByInstance($lead, $data);

        $result = new LeadResource($lead);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("lead updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId, $id)
    {
        $lead = $this->leadRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$lead) {
            throw new NotFoundHttpException();
        }

        $this->leadRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("lead deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, $branchId)
    {
        $ids = $request->ids;

        $count = $this->leadRepository->destroyAllBy(['branch_id' => $branchId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count leads deleted successfully.")
            ->setCode(200);
    }


    public function leadLogs(Request $request)
    {
        $result = $this->leadRepository->getLeadLogs($request);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }



}
