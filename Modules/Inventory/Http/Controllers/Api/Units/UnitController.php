<?php

namespace Modules\Inventory\Http\Controllers\Api\Units;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Inventory\Http\Resources\Units\UnitResource;
use Modules\Inventory\Http\Resources\Units\UnitCollection;
use Modules\Inventory\Http\Requests\Units\StoreUnitRequest;
use Modules\Inventory\Http\Requests\Units\UpdateUnitRequest;
use Modules\Inventory\Http\Resources\Units\UnitBriefResource;
use Modules\Inventory\Repositories\Units\UnitRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UnitController extends ApiController
{
    /** inject required classes */
    public function __construct(protected UnitRepositoryInterface $unitRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_inventory_units')->only('index', 'show');
        $this->middleware('permission:create_inventory_units')->only('store');
        $this->middleware('permission:update_inventory_units')->only('update');
        $this->middleware('permission:delete_inventory_units')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $units = $this->unitRepository->paginate('id');

        $result = new UnitCollection($units);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief()
    {
        $units = $this->unitRepository->getAll();

        $result = UnitBriefResource::collection($units);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreUnitRequest $request)
    {
        $data = $request->validated();

        $unit = $this->unitRepository->store($data);

        $result = new UnitResource($unit);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("unit created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $unit = $this->unitRepository->findById($id, []);

        if (!$unit) {
            throw new NotFoundHttpException();
        }

        $result =  new UnitResource($unit);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateUnitRequest $request, $id)
    {
        $unit = $this->unitRepository->findById($id);

        if (!$unit) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $unit = $this->unitRepository->updateByInstance($unit, $data);

        $result = new UnitResource($unit);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("unit updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $unit = $this->unitRepository->findById($id);

        if (!$unit) {
            throw new NotFoundHttpException();
        }

        $this->unitRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("unit deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->unitRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count units deleted successfully.")
            ->setCode(200);
    }
}
