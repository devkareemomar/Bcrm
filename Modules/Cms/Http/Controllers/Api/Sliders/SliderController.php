<?php

namespace Modules\Cms\Http\Controllers\Api\Sliders;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Sliders\SliderResource;
use Modules\Cms\Http\Resources\Sliders\SliderCollection;
use Modules\Cms\Http\Requests\Sliders\StoreSliderRequest;
use Modules\Cms\Http\Requests\Sliders\UpdateSliderRequest;
use Modules\Cms\Repositories\Sliders\SliderRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SliderController extends ApiController
{
    /** inject required classes */
    public function __construct(protected SliderRepositoryInterface $sliderRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_sliders')->only('index', 'show');
        $this->middleware('permission:create_cms_sliders')->only('store');
        $this->middleware('permission:update_cms_sliders')->only('update');
        $this->middleware('permission:delete_cms_sliders')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $sliders = $this->sliderRepository->paginate('id', ['photo', 'category', 'created_by', 'updated_by']);

        $result = new SliderCollection($sliders);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreSliderRequest $request)
    {
        $data = $request->validated();

        $data['created_by_id'] = Auth::id();

        $slider = $this->sliderRepository->store($data);

        $result = new SliderResource($slider);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("slider created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $slider = $this->sliderRepository->findById($id, ['photo', 'category', 'created_by', 'updated_by']);

        if (!$slider) {
            throw new NotFoundHttpException();
        }

        $result =  new SliderResource($slider);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateSliderRequest $request, $id)
    {
        $slider = $this->sliderRepository->findById($id);

        if (!$slider) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $data['updated_by_id'] = Auth::id();

        $slider = $this->sliderRepository->updateByInstance($slider, $data);

        $result = new sliderResource($slider);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("slider updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $slider = $this->sliderRepository->findById($id);

        if (!$slider) {
            throw new NotFoundHttpException();
        }

        $this->sliderRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("slider deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->sliderRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count sliders deleted successfully.")
            ->setCode(200);
    }
}
