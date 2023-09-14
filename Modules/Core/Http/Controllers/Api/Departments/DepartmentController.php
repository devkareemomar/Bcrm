<?php

namespace Modules\Core\Http\Controllers\Api\Departments;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\Departments\DepartmentResource;
use Modules\Core\Http\Resources\Departments\DepartmentCollection;
use Modules\Core\Http\Requests\Departments\StoreDepartmentRequest;
use Modules\Core\Http\Requests\Departments\UpdateDepartmentRequest;
use Modules\Core\Http\Resources\Departments\DepartmentBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\Departments\DepartmentRepositoryInterface;

class DepartmentController extends ApiController
{
    /** inject required classes */
    public function __construct(protected DepartmentRepositoryInterface $departmentRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_core_departments')->only('index', 'show');
        $this->middleware('permission:create_core_departments')->only('store');
        $this->middleware('permission:update_core_departments')->only('update');
        $this->middleware('permission:delete_core_departments')->only('destroy', 'bulkDestroy');
    }


    public function index($branchId)
    {
        $departments = $this->departmentRepository->paginate('id', ['branch'], paginate: Request::query('limit') ?? 25, parameters: ['branch_id' => $branchId]);

        $result = new DepartmentCollection($departments);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($branchId)
    {
        $departments = $this->departmentRepository->getAll(fields: ['id', 'name'], parameters: ['branch_id' => $branchId]);

        $result = DepartmentBriefResource::collection($departments);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreDepartmentRequest $request)
    {
        $data = $request->validated();

        $department = $this->departmentRepository->store($data);

        $result = new DepartmentResource($department);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("department created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($branchId,$id)
    {
        $department = $this->departmentRepository->findByMany(['id' => $id, 'branch_id' => $branchId], ['branch']);

        if (!$department) {
            throw new NotFoundHttpException();
        }

        $result =  new DepartmentResource($department);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateDepartmentRequest $request,$branchId, $id)
    {
        $department = $this->departmentRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$department) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $department = $this->departmentRepository->updateByInstance($department, $data);

        $result = new DepartmentResource($department);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("department updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId,$id)
    {
        $department = $this->departmentRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$department) {
            throw new NotFoundHttpException();
        }

        $this->departmentRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("department deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request,$branchId)
    {
        $ids = $request->ids;

        $count = $this->departmentRepository->destroyAllBy(['branch_id' => $branchId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count departments deleted successfully.")
            ->setCode(200);
    }
}
