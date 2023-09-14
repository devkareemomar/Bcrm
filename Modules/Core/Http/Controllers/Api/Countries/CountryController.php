<?php

namespace Modules\Core\Http\Controllers\Api\Countries;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\Countries\CountryResource;
use Modules\Core\Http\Resources\Countries\CountryCollection;
use Modules\Core\Http\Requests\Countries\StoreCountryRequest;
use Modules\Core\Http\Requests\Countries\UpdateCountryRequest;
use Modules\Core\Http\Resources\Countries\CountryBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\Countries\CountryRepositoryInterface;

class CountryController extends ApiController
{
    /** inject required classes */
    public function __construct(protected CountryRepositoryInterface $countryRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_core_countries')->only('index', 'show');
        // $this->middleware('permission:create_coree_countries')->only('store');
        // $this->middleware('permission:update_coree_countries')->only('update');
        $this->middleware('permission:delete_core_countries')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $countries = $this->countryRepository->paginate('id', paginate: Request::query('limit') ?? 25,);

        $result = new CountryCollection($countries);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief()
    {
        $countries = $this->countryRepository->getAll(fields: ['id', 'name']);

        $result = CountryBriefResource::collection($countries);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreCountryRequest $request)
    {
        $data = $request->validated();

        $country = $this->countryRepository->store($data);

        $result = new CountryResource($country);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("country created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $country = $this->countryRepository->findById($id, []);

        if (!$country) {
            throw new NotFoundHttpException();
        }

        $result =  new CountryResource($country);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateCountryRequest $request, $id)
    {
        $country = $this->countryRepository->findById($id);

        if (!$country) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $country = $this->countryRepository->updateByInstance($country, $data);

        $result = new CountryResource($country);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("country updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $country = $this->countryRepository->findById($id);

        if (!$country) {
            throw new NotFoundHttpException();
        }

        $this->countryRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("country deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->countryRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count countries deleted successfully.")
            ->setCode(200);
    }
}
