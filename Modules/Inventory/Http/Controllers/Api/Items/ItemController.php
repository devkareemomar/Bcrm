<?php

namespace Modules\Inventory\Http\Controllers\Api\Items;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Inventory\Http\Resources\Items\ItemResource;
use Modules\Inventory\Http\Resources\Items\ItemCollection;
use Modules\Inventory\Http\Requests\Items\StoreItemRequest;
use Modules\Inventory\Http\Requests\Items\UpdateItemRequest;
use Modules\Inventory\Http\Resources\Items\ItemBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Inventory\Repositories\Items\ItemRepositoryInterface;

class ItemController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ItemRepositoryInterface $itemRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_inventory_items')->only('index', 'show');
        $this->middleware('permission:create_inventory_items')->only('store');
        $this->middleware('permission:update_inventory_items')->only('update');
        $this->middleware('permission:delete_inventory_items')->only('destroy', 'bulkDestroy');
    }


    public function index($companyId)
    {
        $items = $this->itemRepository->paginate('id', ['photo'], parameters: ['company_id' => $companyId]);

        $result = new ItemCollection($items);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($companyId)
    {
        $items = $this->itemRepository->getAll(parameters: ['company_id' => $companyId], fields: ['id', 'name','sale_price']);

        $result = ItemBriefResource::collection($items);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreItemRequest $request, $companyId)
    {
        $data = $request->validated();
        $data['company_id'] = $companyId;

        $item = $this->itemRepository->store($data);

        $result = new ItemResource($item);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("item created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($companyId, $id)
    {
        $item = $this->itemRepository->findByMany(['id' => $id, 'company_id' => $companyId], relations: ['photo', 'tax', 'category', 'brand', 'unit']);

        if (!$item) {
            throw new NotFoundHttpException();
        }

        $result =  new ItemResource($item);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateItemRequest $request, $companyId, $id)
    {
        $item = $this->itemRepository->findByMany(['id' => $id, 'company_id' => $companyId]);

        if (!$item) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();
        $data['company_id'] = $companyId;

        $item = $this->itemRepository->updateByInstance($item, $data);

        $result = new ItemResource($item);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("item updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($companyId, $id)
    {
        $item = $this->itemRepository->findByMany(['id' => $id, 'company_id' => $companyId]);


        if (!$item) {
            throw new NotFoundHttpException();
        }

        $this->itemRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("item deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, $companyId)
    {
        $ids = $request->ids;

        $count = $this->itemRepository->destroyAllBy(['company_id' => $companyId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count items deleted successfully.")
            ->setCode(200);
    }
}
