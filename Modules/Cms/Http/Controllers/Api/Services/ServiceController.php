<?php

namespace Modules\Cms\Http\Controllers\Api\Services;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Services\ServiceResource;
use Modules\Cms\Http\Resources\Services\ServiceCollection;
use Modules\Cms\Http\Requests\Services\StoreServiceRequest;
use Modules\Cms\Http\Requests\Services\UpdateServiceRequest;
use Modules\Cms\Repositories\Services\ServiceRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ServiceRepositoryInterface $serviceRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_services')->only('index', 'show');
        $this->middleware('permission:create_cms_services')->only('store');
        $this->middleware('permission:update_cms_services')->only('update');
        $this->middleware('permission:delete_cms_services')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $services = $this->serviceRepository->paginate('id', ['icon', 'photo', 'category', 'created_by', 'updated_by'], [], 25, [], [
            'id', 'title_ar', 'title_en', 'category_id', 'description_ar', 'description_en', 'created_at', 'icon_media_id', 'photo_media_id', 'updated_at', 'created_by_id', 'updated_by_id'
        ]);

        $result = new ServiceCollection($services);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();

        // add rest of the values
        $data['created_by_id'] = Auth::id();

        $service = $this->serviceRepository->store($data);

        $result = new ServiceResource($service);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("service created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $service = $this->serviceRepository->findById($id, ['photo', 'icon', 'category', 'created_by', 'updated_by']);

        if (!$service) {
            throw new NotFoundHttpException();
        }

        $result =  new ServiceResource($service);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateServiceRequest $request, $id)
    {
        $service = $this->serviceRepository->findById($id);

        if (!$service) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        // add rest of the values
        $data['updated_by_id'] = Auth::id();

        $service = $this->serviceRepository->updateByInstance($service, $data);

        $result = new ServiceResource($service);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("service updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $service = $this->serviceRepository->findById($id);

        if (!$service) {
            throw new NotFoundHttpException();
        }

        $this->serviceRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("service deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->serviceRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count services deleted successfully.")
            ->setCode(200);
    }
}
