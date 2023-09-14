<?php

namespace Modules\Inventory\Http\Controllers\Api\Transfers;


use App\Http\Controllers\Api\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Modules\Inventory\Http\Resources\Transfers\TransferResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Inventory\Http\Resources\Transfers\TransferCollection;
use Modules\Inventory\Http\Requests\Transfers\StoreTransferRequest;
use Modules\Inventory\Http\Requests\Transfers\UpdateTransferRequest;
use Modules\Inventory\Repositories\Transfers\TransferRepositoryInterface;

class TransferController extends ApiController
{
    /** inject required classes */
    public function __construct(protected TransferRepositoryInterface $transferRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_inventory_transfers')->only('index', 'show');
        $this->middleware('permission:create_inventory_transfers')->only('store');
        $this->middleware('permission:update_inventory_transfers')->only('update');
        // $this->middleware('permission:delete_inventory_transfers')->only('destroy', 'bulkDestroy');
    }

    public function index()
    {
        $transfers = $this->transferRepository->paginate('id', ['user', 'fromStore', 'toStore']);

        $result = new TransferCollection($transfers);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    // public function brief()
    // {
    //     $transfers = $this->transferRepository->getAll();

    //     $result = TransferBriefResource::collection($transfers);

    //     return $this->jsonResponse()->setStatus(true)
    //         ->setCode(200)
    //         ->setResult($result);
    // }


    public function store(StoreTransferRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['total_quantity'] = array_sum(array_column($data['items'],'quantity'));
        $data['items_count'] = count($data['items']);

        // TOFIX:
        $id = $this->transferRepository->maxId();
        $data['code'] = 'transfer_'.$id;

        $transfer = $this->transferRepository->store($data);

        $result = new TransferResource($transfer);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("transfer created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $transfer = $this->transferRepository->findById($id, ['user', 'fromStore', 'toStore', 'items']);

        if (!$transfer) {
            throw new NotFoundHttpException();
        }

        $result =  new TransferResource($transfer);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateTransferRequest $request, $id)
    {
        $transfer = $this->transferRepository->findById($id);

        if (!$transfer) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['total_quantity'] = array_sum(array_column($data['items'],'quantity'));
        $data['items_count'] = count($data['items']);

        // TOFIX:
        $id = $this->transferRepository->maxId();
        $data['code'] = 'transfer_'.$id;

        $transfer = $this->transferRepository->updateByInstance($transfer, $data);

        $result = new TransferResource($transfer);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("transfer updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    // public function destroy($id)
    // {
    //     $transfer = $this->transferRepository->findById($id);

    //     if (!$transfer) {
    //         throw new NotFoundHttpException();
    //     }

    //     $this->transferRepository->destroy($id);

    //     return $this->jsonResponse()->setStatus(true)
    //         ->setMessage("transfer deleted successfully.")
    //         ->setCode(200);
    // }


    // public function bulkDestroy(BulkDestroyRequest $request)
    // {
    //     $ids = $request->ids;

    //     $count = $this->transferRepository->destroyAll($ids);

    //     return $this->jsonResponse()->setStatus(true)
    //         ->setMessage("$count transfers deleted successfully.")
    //         ->setCode(200);
    // }
}
