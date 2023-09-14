<?php

namespace Modules\Crm\Http\Controllers\Api\SalesOrders;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Crm\Http\Requests\SalesOrders\StoreSalesOrderDetailsRequest;
use Modules\Crm\Http\Requests\SalesOrders\UpdateSalesOrderDetailsRequest;
use Modules\Crm\Http\Resources\ItemDetailsResource;
use Modules\Crm\Repositories\SalesOrders\SalesOrderDetailsRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SalesOrderDetailsController extends ApiController
{
    /** inject required classes */
    public function __construct(protected SalesOrderDetailsRepositoryInterface $SalesOrderDetailsRepository)
    {

        /** Roles and Permissions middlewares */
        $this->middleware('permission:create_crm_salesorders')->only('store');
        $this->middleware('permission:update_crm_salesorders')->only('update');
        $this->middleware('permission:delete_crm_salesorders')->only('destroy', 'bulkDestroy');
    }


    public function store(StoreSalesOrderDetailsRequest $request)
    {
        $data = $request->validated();
        $data['sales_order_id'] = $request->route('sales_order_id');

        $SalesOrderDetails =$this->SalesOrderDetailsRepository->store($data);

        $result['item'] = new ItemDetailsResource($SalesOrderDetails['item']);
        $result['master'] = $SalesOrderDetails['master'];

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("sales order details created successfully.")
                    ->setCode(201)
                    ->setResult($result);
    }


    public function show($id)
    {
        $SalesOrderDetails = $this->SalesOrderDetailsRepository->findById($id, ['item', 'tax','unit']);

        if (!$SalesOrderDetails) {
            throw new NotFoundHttpException();
        }

        $result =  new ItemDetailsResource($SalesOrderDetails);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateSalesOrderDetailsRequest $request)
    {
        $SalesOrderDetails = $this->SalesOrderDetailsRepository->findById($request->route('detail'));

        if (!$SalesOrderDetails) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $SalesOrderDetails = $this->SalesOrderDetailsRepository->updateByInstance($SalesOrderDetails, $data);

        $result['item']   = new ItemDetailsResource($SalesOrderDetails['item']);
        $result['master'] = $SalesOrderDetails['master'];

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("sales order details updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy()
    {
        $id = request()->route('detail');
        $SalesOrderDetails = $this->SalesOrderDetailsRepository->findById($id);

        if (!$SalesOrderDetails) {
            throw new NotFoundHttpException();
        }
        $sales_order_id = request()->route('sales_order_id');

        $this->SalesOrderDetailsRepository->delete($id,$sales_order_id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("sales order details deleted successfully.")
            ->setCode(200);

    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;
        $sales_order_id = request()->route('sales_order_id');

        $result = $this->SalesOrderDetailsRepository->deleteAll($ids,$sales_order_id);
        $count  = $result['count'];

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count sales order details deleted successfully.")
            ->setCode(200);

    }
}
