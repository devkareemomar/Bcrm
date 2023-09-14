<?php

namespace Modules\Core\Http\Controllers\Api\ActivityLogs;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Http\Request;
use Modules\Core\Http\Resources\ActivityLogs\ActivityLogResource;
use Modules\Core\Http\Resources\ActivityLogs\ActivityLogCollection;
use Modules\Core\Http\Requests\ActivityLogs\StoreActivityLogRequest;
use Modules\Core\Http\Requests\ActivityLogs\UpdateActivityLogRequest;
use Modules\Core\Http\Resources\ActivityLogs\ActivityLogBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\ActivityLogs\ActivityLogRepositoryInterface;

class ActivityLogController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ActivityLogRepositoryInterface $activitylogRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_core_activitylogs')->only('index', 'show');
        $this->middleware('permission:delete_core_activitylogs')->only('destroy', 'bulkDestroy');
    }


    public function index(Request $request)
    {
        $activitylogs = $this->activitylogRepository->paginate(paginate: $request->limit ?? 15);

        $result = ActivityLogResource::collection($activitylogs);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    // public function brief()
    // {
    //     $activitylogs = $this->activitylogRepository->getAll();

    //     $result = ActivityLogBriefResource::collection($activitylogs);

    //     return $this->jsonResponse()->setStatus(true)
    //         ->setCode(200)
    //         ->setResult($result);
    // }


    public function show($id)
    {
        $activitylog = $this->activitylogRepository->findById($id, []);

        if (!$activitylog) {
            throw new NotFoundHttpException();
        }

        $result =  new ActivityLogResource($activitylog);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    // public function destroy($id)
    // {
    //     $activitylog = $this->activitylogRepository->findById($id);

    //     if (!$activitylog) {
    //         throw new NotFoundHttpException();
    //     }

    //     $this->activitylogRepository->destroy($id);

    //     return $this->jsonResponse()->setStatus(true)
    //         ->setMessage("activitylog deleted successfully.")
    //         ->setCode(200);
    // }


    // public function bulkDestroy(BulkDestroyRequest $request)
    // {
    //     $ids = $request->ids;

    //     $count = $this->activitylogRepository->destroyAll($ids);

    //     return $this->jsonResponse()->setStatus(true)
    //         ->setMessage("$count activitylogs deleted successfully.")
    //         ->setCode(200);
    // }
}
