<?php

namespace Modules\Core\Http\Controllers\Api\Branches;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\Branches\BranchResource;
use Modules\Core\Http\Resources\Branches\BranchCollection;
use Modules\Core\Http\Requests\Branches\StoreBranchRequest;
use Modules\Core\Http\Requests\Branches\UpdateBranchRequest;
use Modules\Core\Http\Resources\Branches\BranchBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\Branches\BranchRepositoryInterface;

class BranchController extends ApiController
{
    /** inject required classes */
    public function __construct(protected BranchRepositoryInterface $branchRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('role:super_admin')->except('brief');
    }


    public function index()
    {
        $branches = $this->branchRepository->paginate(
            orderBy: 'id',
            paginate: Request::query('limit') ?? 25,
            relations: ['company', 'city'],
            fields: ['id', 'name', 'address', 'phone', 'email', 'company_id', 'city_id']
        );
        // dd($branches);
        $result = new BranchCollection($branches);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief()
    {
        $branches = $this->branchRepository->getAll(fields: ['id', 'name']);

        $result = BranchBriefResource::collection($branches);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function briefByCompanyId($companyId)
    {
        $branches = $this->branchRepository->getAll(parameters:['company_id'=>$companyId],fields: ['id', 'name']);

        $result = BranchBriefResource::collection($branches);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function store(StoreBranchRequest $request)
    {
        $data = $request->validated();

        $branch = $this->branchRepository->store($data);

        $result = new BranchResource($branch);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("branch created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $branch = $this->branchRepository->findById($id, ['logo', 'header', 'footer', 'stamp', 'signature', 'document', 'company', 'city']);

        if (!$branch) {
            throw new NotFoundHttpException();
        }

        $result =  new BranchResource($branch);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateBranchRequest $request, $id)
    {
        $branch = $this->branchRepository->findById($id);

        if (!$branch) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $branch = $this->branchRepository->updateByInstance($branch, $data);

        $result = new BranchResource($branch);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("branch updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $branch = $this->branchRepository->findById($id);

        if (!$branch) {
            throw new NotFoundHttpException();
        }

        $this->branchRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("branch deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->branchRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count branches deleted successfully.")
            ->setCode(200);
    }
}
