<?php

namespace Modules\Cms\Http\Controllers\Api\Pages;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Pages\PageResource;
use Modules\Cms\Http\Resources\Pages\PageCollection;
use Modules\Cms\Http\Requests\Pages\StorePageRequest;
use Modules\Cms\Http\Requests\Pages\UpdatePageRequest;
use Modules\Cms\Repositories\Pages\PageRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends ApiController
{
    /** inject required classes */
    public function __construct(protected PageRepositoryInterface $pageRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_pages')->only('index', 'show');
        $this->middleware('permission:create_cms_pages')->only('store');
        $this->middleware('permission:update_cms_pages')->only('update');
        $this->middleware('permission:delete_cms_pages')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $pages = $this->pageRepository->paginate('id', ['photo', 'icon', 'created_by', 'updated_by'], [], 25, [], [
            'id', 'title_ar', 'title_en', 'description_ar', 'description_en', 'type', 'created_at', 'icon_media_id', 'photo_media_id', 'updated_at', 'created_by_id', 'updated_by_id'
        ]);

        $result = new PageCollection($pages);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StorePageRequest $request)
    {
        $data = $request->validated();

        // add rest of the values
        $data['created_by_id'] = Auth::id();

        $page = $this->pageRepository->store($data);

        $result = new PageResource($page);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("page created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $page = $this->pageRepository->findById($id, ['photo', 'icon', 'created_by', 'updated_by']);

        if (!$page) {
            throw new NotFoundHttpException();
        }

        $result =  new PageResource($page);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdatePageRequest $request, $id)
    {
        $page = $this->pageRepository->findById($id);

        if (!$page) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        // add rest of the values
        $data['updated_by_id'] = Auth::id();

        $page = $this->pageRepository->updateByInstance($page, $data);

        $result = new PageResource($page);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("page updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $page = $this->pageRepository->findById($id);

        if (!$page) {
            throw new NotFoundHttpException();
        }

        $this->pageRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("page deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->pageRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count pages deleted successfully.")
            ->setCode(200);
    }
}
