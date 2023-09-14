<?php

namespace Modules\Inventory\Http\Controllers\Api\Quantities;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Core\Repositories\Branches\BranchRepositoryInterface;
use Modules\Inventory\Http\Resources\Quantities\QuantityResource;
use Modules\Inventory\Repositories\Items\ItemRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Inventory\Http\Resources\Quantities\QuantityCollection;
use Modules\Inventory\Repositories\Stores\StoreRepositoryInterface;
use Modules\Inventory\Http\Requests\Quantities\StoreQuantityRequest;
use Modules\Inventory\Http\Requests\Quantities\UpdateQuantityRequest;
use Modules\Inventory\Http\Resources\Quantities\QuantityBriefResource;
use Modules\Inventory\Repositories\Quantities\QuantityRepositoryInterface;

class QuantityController extends ApiController
{
    /** inject required classes */
    public function __construct(
        protected StoreRepositoryInterface $storeRepository,
        protected QuantityRepositoryInterface $quantityRepository,
    ) {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_inventory_quantities')->only('index', 'show');
        $this->middleware('permission:create_inventory_quantities')->only('store');
        $this->middleware('permission:update_inventory_quantities')->only('update');
        $this->middleware('permission:delete_inventory_quantities')->only('destroy', 'bulkDestroy');
        $this->checkStore(request()->route('branch'), request()->route('store'));
    }


    public function index($branchId, $storeId)
    {
        $quantities = $this->quantityRepository->paginate('id', ['item'], parameters: ['store_id' => $storeId]);

        $result = new QuantityCollection($quantities);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($branchId, $storeId)
    {
        $quantities = $this->quantityRepository->getAll('id', ['item'], parameters: ['store_id' => $storeId]);

        $result = QuantityBriefResource::collection($quantities);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreQuantityRequest $request, $branchId, $storeId)
    {
        $data = $request->validated();
        $data['store_id'] = $storeId;

        $quantity = $this->quantityRepository->store($data);

        $result = new QuantityResource($quantity);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quantity created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($branchId, $storeId, $id)
    {
        $quantity = $this->quantityRepository
            ->findByMany(['id' => $id, 'store_id' => $storeId], ['item']);

        if (!$quantity) {
            throw new NotFoundHttpException();
        }

        $result =  new QuantityResource($quantity);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateQuantityRequest $request, $branchId, $storeId, $id)
    {
        $quantity = $this->quantityRepository
            ->findByMany(['id' => $id, 'store_id' => $storeId]);

        if (!$quantity) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $quantity = $this->quantityRepository->updateByInstance($quantity, $data);

        $result = new QuantityResource($quantity);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quantity updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId, $storeId, $id)
    {
        $quantity = $this->quantityRepository
            ->findByMany(['id' => $id, 'store_id' => $storeId]);

        if (!$quantity) {
            throw new NotFoundHttpException();
        }

        $this->quantityRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quantity deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, $branchId, $storeId)
    {
        $ids = $request->ids;

        $count = $this->quantityRepository->destroyAllBy(['store_id' => $storeId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count quantities deleted successfully.")
            ->setCode(200);
    }


    private function checkStore($branchId, $storeId)
    {
        $store = $this->storeRepository->findByMany(['id' => $storeId, 'branch_id' => $branchId]);

        if (!$store) {
            throw new NotFoundHttpException();
        }

        return $store;
    }
}
