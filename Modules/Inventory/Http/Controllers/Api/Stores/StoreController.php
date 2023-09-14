<?php

namespace Modules\Inventory\Http\Controllers\Api\Stores;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Inventory\Http\Resources\Stores\StoreResource;
use Modules\Inventory\Http\Resources\Stores\StoreCollection;
use Modules\Inventory\Http\Requests\Stores\StoreStoreRequest;
use Modules\Inventory\Http\Requests\Stores\UpdateStoreRequest;
use Modules\Inventory\Http\Resources\Stores\StoreBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Inventory\Repositories\Stores\StoreRepositoryInterface;

class StoreController extends ApiController
{
    /** inject required classes */
    public function __construct(protected StoreRepositoryInterface $storeRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_inventory_stores')->only('index', 'show');
        $this->middleware('permission:create_inventory_stores')->only('store');
        $this->middleware('permission:update_inventory_stores')->only('update');
        $this->middleware('permission:delete_inventory_stores')->only('destroy', 'bulkDestroy');
    }


    public function index($branchId)
    {
        $stores = $this->storeRepository->paginate('id', ['storekeeper'], parameters: ['branch_id' => $branchId]);

        $result = new StoreCollection($stores);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($branchId)
    {
        $stores = $this->storeRepository->getAll(parameters: ['branch_id' => $branchId], fields: ['id', 'name']);

        $result = StoreBriefResource::collection($stores);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreStoreRequest $request, $branchId)
    {
        $data = $request->validated();
        $data['branch_id'] = $branchId;

        $store = $this->storeRepository->store($data);

        $result = new StoreResource($store);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("store created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($branchId, $id)
    {
        $store = $this->storeRepository->findByMany(['id' => $id, 'branch_id' => $branchId], ['storekeeper']);

        if (!$store) {
            throw new NotFoundHttpException();
        }

        $result =  new StoreResource($store);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateStoreRequest $request, $branchId, $id)
    {
        $store = $this->storeRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$store) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $store = $this->storeRepository->updateByInstance($store, $data);

        $result = new StoreResource($store);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("store updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId, $id)
    {
        $store = $this->storeRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$store) {
            throw new NotFoundHttpException();
        }

        $this->storeRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("store deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, $branchId)
    {
        $ids = $request->ids;

        $count = $this->storeRepository->destroyAllBy(['branch_id' => $branchId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count stores deleted successfully.")
            ->setCode(200);
    }
}
