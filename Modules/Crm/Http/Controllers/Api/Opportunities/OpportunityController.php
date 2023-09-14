<?php

namespace Modules\Crm\Http\Controllers\Api\Opportunities;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Crm\Http\Resources\Opportunities\OpportunityResource;
use Modules\Crm\Http\Resources\Opportunities\OpportunityCollection;
use Modules\Crm\Http\Requests\Opportunities\StoreOpportunityRequest;
use Modules\Crm\Http\Requests\Opportunities\UpdateOpportunityRequest;
use Modules\Crm\Http\Resources\Opportunities\OpportunityBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\Opportunities\OpportunityRepositoryInterface;

class OpportunityController extends ApiController
{
    /** inject required classes */
    public function __construct(protected OpportunityRepositoryInterface $opportunityRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_opportunities')->only('index', 'show');
        $this->middleware('permission:create_crm_opportunities')->only('store');
        $this->middleware('permission:update_crm_opportunities')->only('update');
        $this->middleware('permission:delete_crm_opportunities')->only('destroy', 'bulkDestroy');
    }


    public function index($branchId)
    {
        $opportunities = $this->opportunityRepository->paginate('id',['department','assign_to'],paginate:Request::query('limit') ?? 25,  parameters: ['branch_id' => $branchId]);

        $result = new OpportunityCollection($opportunities);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($branchId)
    {
        $opportunities = $this->opportunityRepository->getAll(fields: ['id', 'subject'], parameters: ['branch_id' => $branchId]);

        $result = OpportunityBriefResource::collection($opportunities);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreOpportunityRequest $request,$branchId)
    {

        $data = $request->validated();
        $data['branch_id'] = $branchId;

        $opportunity = $this->opportunityRepository->store($data);

        $result = new OpportunityResource($opportunity);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("opportunity created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($branchId,$id)
    {
        $opportunity = $this->opportunityRepository->findByMany(['id' => $id, 'branch_id' => $branchId],
        ['currency', 'lead', 'opportunityDetails','opportunityDetails.item','opportunityDetails.unit','opportunityDetails.tax', 'department','team','assign_to','paymentTerms','branch']);

        if (!$opportunity) {
            throw new NotFoundHttpException();
        }

        $result =  new OpportunityResource($opportunity);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateOpportunityRequest $request,$branchId, $id)
    {
        $opportunity = $this->opportunityRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$opportunity) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $opportunity = $this->opportunityRepository->updateByInstance($opportunity, $data);

        $result = new OpportunityResource($opportunity);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("opportunity updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId,$id)
    {
        $opportunity = $this->opportunityRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$opportunity) {
            throw new NotFoundHttpException();
        }

        $this->opportunityRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("opportunity deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request,$branchId)
    {
        $ids = $request->ids;

        $count = $this->opportunityRepository->destroyAllBy(['branch_id' => $branchId],$ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count opportunities deleted successfully.")
            ->setCode(200);
    }
}
