<?php

namespace Modules\Core\Http\Controllers\Api\Cities;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\Cities\CityResource;
use Modules\Core\Http\Resources\Cities\CityCollection;
use Modules\Core\Http\Requests\Cities\StoreCityRequest;
use Modules\Core\Http\Requests\Cities\UpdateCityRequest;
use Modules\Core\Http\Resources\Cities\CityBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\Cities\CityRepositoryInterface;

class CityController extends ApiController
{
    /** inject required classes */
    public function __construct(protected CityRepositoryInterface $cityRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_core_cities')->only('index', 'show');
        $this->middleware('permission:create_core_cities')->only('store');
        $this->middleware('permission:update_core_cities')->only('update');
        $this->middleware('permission:delete_core_cities')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $cities = $this->cityRepository->paginate('id', ['state'], paginate: Request::query('limit') ?? 25,);

        $result = new CityCollection($cities);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief()
    {
        $cities = $this->cityRepository->getAll(fields: ['id', 'name']);

        $result = CityBriefResource::collection($cities);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreCityRequest $request)
    {
        $data = $request->validated();

        $city = $this->cityRepository->store($data);

        $result = new CityResource($city);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("city created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $city = $this->cityRepository->findById($id, ['state']);

        if (!$city) {
            throw new NotFoundHttpException();
        }

        $result =  new CityResource($city);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateCityRequest $request, $id)
    {
        $city = $this->cityRepository->findById($id);

        if (!$city) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $city = $this->cityRepository->updateByInstance($city, $data);

        $result = new CityResource($city);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("city updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $city = $this->cityRepository->findById($id);

        if (!$city) {
            throw new NotFoundHttpException();
        }

        $this->cityRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("city deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->cityRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count cities deleted successfully.")
            ->setCode(200);
    }
}
