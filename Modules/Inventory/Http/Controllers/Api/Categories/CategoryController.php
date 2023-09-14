<?php

namespace Modules\Inventory\Http\Controllers\Api\Categories;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Inventory\Http\Resources\Categories\CategoryResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Inventory\Http\Resources\Categories\CategoryCollection;
use Modules\Inventory\Http\Requests\Categories\StoreCategoryRequest;
use Modules\Inventory\Http\Requests\Categories\UpdateCategoryRequest;
use Modules\Inventory\Http\Resources\Categories\CategoryBriefResource;
use Modules\Inventory\Repositories\Categories\CategoryRepositoryInterface;

class CategoryController extends ApiController
{
    /** inject required classes */
    public function __construct(protected CategoryRepositoryInterface $categoryRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_inventory_categories')->only('index', 'show');
        $this->middleware('permission:create_inventory_categories')->only('store');
        $this->middleware('permission:update_inventory_categories')->only('update');
        $this->middleware('permission:delete_inventory_categories')->only('destroy', 'bulkDestroy');
    }


    public function index($companyId)
    {
        $categories = $this->categoryRepository->paginate('id', parameters: ['company_id' => $companyId]);

        $result = new CategoryCollection($categories);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($companyId)
    {
        $categories = $this->categoryRepository->getAll(parameters: ['company_id' => $companyId]);

        $result = CategoryBriefResource::collection($categories);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreCategoryRequest $request, $companyId)
    {
        $data = $request->validated();
        $data['company_id'] = $companyId;

        $category = $this->categoryRepository->store($data);

        $result = new CategoryResource($category);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("category created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($companyId, $id)
    {
        $category = $this->categoryRepository->findByMany(['id' => $id, 'company_id' => $companyId]);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $result =  new CategoryResource($category);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateCategoryRequest $request, $companyId, $id)
    {
        $category = $this->categoryRepository->findByMany(['id' => $id, 'company_id' => $companyId]);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();
        $data['company_id'] = $companyId;

        $category = $this->categoryRepository->updateByInstance($category, $data);

        $result = new CategoryResource($category);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("category updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($companyId, $id)
    {
        $category = $this->categoryRepository->findByMany(['id' => $id, 'company_id' => $companyId]);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $this->categoryRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("category deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, $companyId)
    {
        $ids = $request->ids;

        $count = $this->categoryRepository->destroyAllBy(['company_id' => $companyId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count categories deleted successfully.")
            ->setCode(200);
    }
}
