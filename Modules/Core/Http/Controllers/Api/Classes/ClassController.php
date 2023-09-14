<?php

namespace Modules\Core\Http\Controllers\Api\Classes;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\Classes\ClassResource;
use Modules\Core\Http\Resources\Classes\ClassCollection;
use Modules\Core\Http\Requests\Classes\StoreClassRequest;
use Modules\Core\Http\Requests\Classes\UpdateClassRequest;
use Modules\Core\Http\Resources\Classes\ClassBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\Classes\ClassRepositoryInterface;

class ClassController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ClassRepositoryInterface $classRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_core_classes')->only('index', 'show');
        $this->middleware('permission:create_core_classes')->only('store');
        $this->middleware('permission:update_core_classes')->only('update');
        $this->middleware('permission:delete_core_classes')->only('destroy', 'bulkDestroy');

    }


    public function index($companyId)
    {
        $classes = $this->classRepository->paginate('id',parameters: ['company_id' => $companyId] ,paginate: Request::query('limit') ?? 25,);

        $result = new ClassCollection($classes);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief($companyId)
    {
        $classes = $this->classRepository->getAll(parameters: ['company_id' => $companyId],);

        $result = ClassBriefResource::collection($classes);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreClassRequest $request,$companyId)
    {
        $data = $request->validated();
        $data['company_id'] = $companyId;

        $class = $this->classRepository->store($data);

        $result = new ClassResource($class);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("class created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($companyId,$id)
    {
        $class = $this->classRepository->findByMany(['id' => $id, 'company_id' => $companyId]);

        if (!$class) {
            throw new NotFoundHttpException();
        }

        $result =  new ClassResource($class);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateClassRequest $request,$companyId, $id)
    {
        $class = $this->classRepository->findByMany(['id' => $id, 'company_id' => $companyId]);
        if (!$class) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $class = $this->classRepository->updateByInstance($class, $data);

        $result = new ClassResource($class);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("class updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($companyId,$id)
    {
        $class = $this->classRepository->findByMany(['id' => $id, 'company_id' => $companyId]);

        if (!$class) {
            throw new NotFoundHttpException();
        }

        $this->classRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("class deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, $companyId)
    {
        $ids = $request->ids;

        $count = $this->classRepository->destroyAllBy(['company_id' => $companyId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count classes deleted successfully.")
            ->setCode(200);
    }
}
