<?php

namespace Modules\Inventory\Http\Controllers\Api\Brands;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Core\Http\Middleware\EnsureCompanyMiddleware;
use Modules\Inventory\Http\Resources\Brands\BrandResource;
use Modules\Inventory\Http\Resources\Brands\BrandCollection;
use Modules\Inventory\Http\Requests\Brands\StoreBrandRequest;
use Modules\Inventory\Http\Requests\Brands\UpdateBrandRequest;
use Modules\Inventory\Http\Resources\Brands\BrandBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Inventory\Repositories\Brands\BrandRepositoryInterface;

class BrandController extends ApiController
{
    /** inject required classes */
    public function __construct(protected BrandRepositoryInterface $brandRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_inventory_brands')->only('index', 'show');
        $this->middleware(['permission:create_inventory_brands', EnsureCompanyMiddleware::class])->only('store');
        $this->middleware('permission:update_inventory_brands',)->only('update');
        $this->middleware('permission:delete_inventory_brands')->only('destroy', 'bulkDestroy');
    }


    public function index($companyId)
    {
        $brands = $this->brandRepository->paginate('id', parameters: ['company_id' => $companyId]);

        $result = new BrandCollection($brands);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($companyId)
    {
        $brands = $this->brandRepository->getAll(parameters: ['company_id' => $companyId]);

        $result = BrandBriefResource::collection($brands);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreBrandRequest $request, $companyId)
    {
        $data = $request->validated();
        $data['company_id'] = $companyId;

        $brand = $this->brandRepository->store($data);

        $result = new BrandResource($brand);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("brand created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($companyId, $id)
    {
        $brand = $this->brandRepository->findByMany(['id' => $id, 'company_id' => $companyId]);

        if (!$brand) {
            throw new NotFoundHttpException();
        }

        $result =  new BrandResource($brand);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateBrandRequest $request, $companyId, $id)
    {
        $brand = $this->brandRepository->findByMany(['id' => $id, 'company_id' => $companyId]);

        if (!$brand) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $brand = $this->brandRepository->updateByInstance($brand, $data);

        $result = new BrandResource($brand);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("brand updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($companyId, $id)
    {
        $brand = $this->brandRepository->findByMany(['id' => $id, 'company_id' => $companyId]);

        if (!$brand) {
            throw new NotFoundHttpException();
        }

        $this->brandRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("brand deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, $companyId)
    {
        $ids = $request->ids;

        $count = $this->brandRepository->destroyAllBy(['company_id' => $companyId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count brands deleted successfully.")
            ->setCode(200);
    }
}
