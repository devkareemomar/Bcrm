<?php

namespace Modules\Cms\Http\Controllers\Api\Galleries;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Galleries\GalleryResource;
use Modules\Cms\Http\Resources\Galleries\GalleryCollection;
use Modules\Cms\Http\Requests\Galleries\StoreGalleryRequest;
use Modules\Cms\Http\Requests\Galleries\UpdateGalleryRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Cms\Repositories\Galleries\GalleryRepositoryInterface;

class GalleryController extends ApiController
{
    /** inject required classes */
    public function __construct(protected GalleryRepositoryInterface $galleryRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_galleries')->only('index', 'show');
        $this->middleware('permission:create_cms_galleries')->only('store');
        $this->middleware('permission:update_cms_galleries')->only('udate');
        $this->middleware('permission:delete_cms_galleries')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $galleries = $this->galleryRepository->paginate('id', ['file', 'category', 'created_by', 'updated_by']);

        $result = new GalleryCollection($galleries);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreGalleryRequest $request)
    {
        $data = $request->validated();

        $data['created_by_id'] = Auth::id();

        $gallery = $this->galleryRepository->store($data);

        $result = new GalleryResource($gallery);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("gallery created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $gallery = $this->galleryRepository->findById($id, ['file', 'category', 'created_by', 'updated_by']);

        if (!$gallery) {
            throw new NotFoundHttpException();
        }

        $result =  new GalleryResource($gallery);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateGalleryRequest $request, $id)
    {
        $gallery = $this->galleryRepository->findById($id);

        if (!$gallery) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        // add rest of the values
        $data['updated_by_id'] = Auth::id();

        $gallery = $this->galleryRepository->updateByInstance($gallery, $data);

        $result = new GalleryResource($gallery);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("gallery updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $gallery = $this->galleryRepository->findById($id);

        if (!$gallery) {
            throw new NotFoundHttpException();
        }

        $this->galleryRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("gallery deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->galleryRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("$count galleries deleted successfully.")
                    ->setCode(200);
    }
}
