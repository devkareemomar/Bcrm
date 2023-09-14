<?php

namespace Modules\Crm\Http\Controllers\Api\LeadStages;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Modules\Crm\Http\Resources\LeadStages\LeadStageResource;
use Modules\Crm\Http\Resources\LeadStages\LeadStageCollection;
use Modules\Crm\Http\Requests\LeadStages\StoreLeadStageRequest;
use Modules\Crm\Http\Requests\LeadStages\UpdateLeadStageRequest;
use Modules\Crm\Http\Resources\LeadStages\LeadStageBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\LeadStages\LeadStageRepositoryInterface;

class LeadStageController extends ApiController
{
    /** inject required classes */
    public function __construct(protected LeadStageRepositoryInterface $leadstageRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_leadstages')->only('index', 'show');
        $this->middleware('permission:create_crm_leadstages')->only('store');
        $this->middleware('permission:update_crm_leadstages')->only('update');
        $this->middleware('permission:delete_crm_leadstages')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $leadstages = $this->leadstageRepository->paginate('id',paginate:Request::query('limit') ?? 25,);

        $result = new LeadStageCollection($leadstages);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief()
    {
        $leadstages = $this->leadstageRepository->getAll();

        $result = LeadStageResource::collection($leadstages);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreLeadStageRequest $request)
    {
        $data = $request->validated();
        $leadstage = $this->leadstageRepository->store($data);

        $result = new LeadStageResource($leadstage);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("leadstage created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $leadstage = $this->leadstageRepository->findById($id, []);

        if (!$leadstage) {
            throw new NotFoundHttpException();
        }

        $result =  new LeadStageResource($leadstage);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateLeadStageRequest $request, $id)
    {
        $leadstage = $this->leadstageRepository->findById($id);

        if (!$leadstage) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $leadstage = $this->leadstageRepository->updateByInstance($leadstage, $data);

        $result = new LeadStageResource($leadstage);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("leadstage updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $leadstage = $this->leadstageRepository->findById($id);

        if (!$leadstage) {
            throw new NotFoundHttpException();
        }

        $this->leadstageRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("leadstage deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->leadstageRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count leadstages deleted successfully.")
            ->setCode(200);
    }



}
