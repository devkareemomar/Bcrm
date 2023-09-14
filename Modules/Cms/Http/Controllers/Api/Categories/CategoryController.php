<?php

namespace Modules\Cms\Http\Controllers\Api\Categories;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Categories\CategoryResource;
use Modules\Cms\Http\Resources\Categories\CategoryCollection;
use Modules\Cms\Http\Requests\Categories\StoreCategoryRequest;
use Modules\Cms\Http\Requests\Categories\UpdateCategoryRequest;
use Modules\Cms\Http\Resources\Categories\BriefCategoryResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Cms\Repositories\Categories\CategoryRepositoryInterface;

class CategoryController extends ApiController
{
    /** inject required classes */
    public function __construct(protected CategoryRepositoryInterface $categoryRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_categories')->only('index', 'show');
        $this->middleware('permission:create_cms_categories')->only('store');
        $this->middleware('permission:update_cms_categories')->only('update');
        $this->middleware('permission:delete_cms_categories')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $categories = $this->categoryRepository->paginate('id', ['photo', 'created_by', 'updated_by']);

        $result = new CategoryCollection($categories);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief()
    {
        $categories = $this->categoryRepository->getAll();

        $result = BriefCategoryResource::collection($categories);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        // add rest of the values
        $data['created_by_id'] = Auth::id();

        $category = $this->categoryRepository->store($data);

        $result = new CategoryResource($category);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("category created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $category = $this->categoryRepository->findById($id, ['photo', 'created_by', 'updated_by']);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $result =  new CategoryResource($category);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        // add rest of the values
        $data['updated_by_id'] = Auth::id();

        $category = $this->categoryRepository->updateByInstance($category, $data);

        $result = new CategoryResource($category);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("category updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $category = $this->categoryRepository->findById($id);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $this->categoryRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("category deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->categoryRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count categories deleted successfully.")
            ->setCode(200);
    }
}
