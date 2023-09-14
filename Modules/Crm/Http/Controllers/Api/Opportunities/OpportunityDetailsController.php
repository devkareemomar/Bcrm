<?php

namespace Modules\Crm\Http\Controllers\Api\Opportunities;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Crm\Http\Requests\Opportunities\StoreOpportunityDetailsRequest;
use Modules\Crm\Http\Resources\Opportunities\OpportunityCollection;
use Modules\Crm\Http\Requests\Opportunities\UpdateOpportunityDetailsRequest;
use Modules\Crm\Http\Resources\ItemDetailsResource;
use Modules\Crm\Http\Resources\Opportunities\OpportunityDetailsResource;
use Modules\Crm\Repositories\Opportunities\OpportunityDetailsRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OpportunityDetailsController extends ApiController
{
    /** inject required classes */
    public function __construct(protected OpportunityDetailsRepositoryInterface $opportunityDetailsRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:create_crm_opportunities')->only('store');
        $this->middleware('permission:update_crm_opportunities')->only('update');
        $this->middleware('permission:delete_crm_opportunities')->only('destroy', 'bulkDestroy');
    }


    public function store(StoreOpportunityDetailsRequest $request)
    {

        $data = $request->validated();
        $data['opportunity_id'] = $request->route('opportunity_id');

        $opportunityDetails =$this->opportunityDetailsRepository->store($data);

        $result['item'] = new ItemDetailsResource($opportunityDetails['item']);
        $result['master'] = $opportunityDetails['master'];

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("opportunity details created successfully.")
                    ->setCode(201)
                    ->setResult($result);
    }


    public function update(UpdateOpportunityDetailsRequest $request)
    {
        $opportunityDetails = $this->opportunityDetailsRepository->findById($request->route('detail'));

        if (!$opportunityDetails) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $opportunityDetails = $this->opportunityDetailsRepository->updateByInstance($opportunityDetails, $data);

        $result['item']   = new ItemDetailsResource($opportunityDetails['item']);
        $result['master'] = $opportunityDetails['master'];

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("opportunity details updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy()
    {
        $id = request()->route('detail');
        $opportunity_id = request()->route('opportunity_id');

        $result = $this->opportunityDetailsRepository->delete($id,$opportunity_id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("opportunity details deleted successfully.")
            ->setCode(200)
            ->setResult($result);

    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;
        $opportunity_id = request()->route('opportunity_id');

        $result = $this->opportunityDetailsRepository->deleteAll($ids,$opportunity_id);
        $count  = $result['count'];

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count opportunity details deleted successfully.")
            ->setCode(200)
            ->setResult($result['master']);
    }
}
