<?php

namespace Modules\Crm\Http\Controllers\Api\Activities;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Http\Request;
use Modules\Crm\Enums\ActivitableTypeEnum;
use Modules\Crm\Http\Resources\Activities\ActivityResource;
use Modules\Crm\Http\Resources\Activities\ActivityCollection;
use Modules\Crm\Http\Requests\Activities\StoreActivityRequest;
use Modules\Crm\Http\Requests\Activities\StoreBulkActivityRequest;
use Modules\Crm\Http\Requests\Activities\UpdateActivityRequest;
use Modules\Crm\Http\Resources\Activities\ActivityBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\Activities\ActivityRepositoryInterface;

class ActivityController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ActivityRepositoryInterface $activityRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_activities')->only('index', 'show');
        $this->middleware('permission:create_crm_activities')->only('store');
        $this->middleware('permission:update_crm_activities')->only('update');
        $this->middleware('permission:delete_crm_activities')->only('destroy', 'bulkDestroy');
    }


    public function index(Request $request,$branchId)
    {

        $activities = $this->activityRepository->paginate(
            'id',
            ['assignTo'],
            parameters: [
                'branch_id' => $branchId ,
                'activitable_type' => 'Modules\Crm\Models\\' . ucfirst($request->activitable_type),
                'activitable_id' => $request->activitable_id],
            paginate: $request->limit,
        );


        $result = new ActivityCollection($activities);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($branchId)
    {
        $activities = $this->activityRepository->getAll(parameters: ['branch_id' => $branchId]);

        $result = ActivityBriefResource::collection($activities);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreActivityRequest $request, $branchId)
    {
        $data = $request->validated();
        $data['created_by_id'] = auth()->user()->id;
        $data['branch_id'] = $branchId;
        $data['activitable_type'] = 'Modules\Crm\Models\\' . $data['activitable_type'];
        // TOFIX:
        $id = $this->activityRepository->maxId();
        $data['code'] = 'activity_' . $id;

        $activity = $this->activityRepository->store($data);

        $result = new ActivityResource($activity);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("activity created successfully.")
            ->setCode(201)
            ->setResult($result);
    }

    public function bulkStore(StoreBulkActivityRequest $request, $branchId)
    {
        $data = $request->validated();
        $this->activityRepository->bulkStore($data,$branchId);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("activities created successfully.")
            ->setCode(201);
    }

    public function show($branchId, $id)
    {
        $activity = $this->activityRepository->findByMany(['id' => $id, 'branch_id' => $branchId], ['createdBy', 'assignTo']);

        if (!$activity) {
            throw new NotFoundHttpException();
        }

        $result =  new ActivityResource($activity);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateActivityRequest $request, $branchId, $id)
    {
        $activity = $this->activityRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$activity) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();
        $data['activitable_type'] = 'Modules\Crm\Models\\' . $data['activitable_type'];

        $activity = $this->activityRepository->updateByInstance($activity, $data);

        $result = new ActivityResource($activity);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("activity updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId, $id)
    {
        $activity = $this->activityRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$activity) {
            throw new NotFoundHttpException();
        }

        $this->activityRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("activity deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, $branchId)
    {
        $ids = $request->ids;

        $count = $this->activityRepository->destroyAllBy(['branch_id' => $branchId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count activities deleted successfully.")
            ->setCode(200);
    }

    public function activityType()
    {
        $result = ActivitableTypeEnum::values();

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function activityByDate(Request $request, $branchId)
    {
        $result = $this->activityRepository->getAllByDate($request, $branchId);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }
}
