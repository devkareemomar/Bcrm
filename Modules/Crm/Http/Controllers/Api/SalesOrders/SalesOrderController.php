<?php

namespace Modules\Crm\Http\Controllers\Api\SalesOrders;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Crm\Http\Resources\SalesOrders\SalesOrderResource;
use Modules\Crm\Http\Resources\SalesOrders\SalesOrderCollection;
use Modules\Crm\Http\Requests\SalesOrders\StoreSalesOrderRequest;
use Modules\Crm\Http\Requests\SalesOrders\UpdateSalesOrderRequest;
use Modules\Crm\Http\Resources\SalesOrders\SalesOrderBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\SalesOrders\SalesOrderRepositoryInterface;

class SalesOrderController extends ApiController
{
    /** inject required classes */
    public function __construct(protected SalesOrderRepositoryInterface $salesorderRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_salesorders')->only('index', 'show');
        $this->middleware('permission:create_crm_salesorders')->only('store');
        $this->middleware('permission:update_crm_salesorders')->only('update');
        $this->middleware('permission:delete_crm_salesorders')->only('destroy', 'bulkDestroy');
    }


    public function index($branchId)
    {

        $salesorders = $this->salesorderRepository->paginate('id',paginate:Request::query('limit') ?? 25,parameters: ['branch_id' => $branchId]);

        $result = new SalesOrderCollection($salesorders);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief($branchId)
    {
        $salesorders = $this->salesorderRepository->getAll(parameters: ['branch_id' => $branchId]);

        $result = SalesOrderBriefResource::collection($salesorders);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreSalesOrderRequest $request,$branchId)
    {
        $data = $request->validated();
        $data['branch_id'] = $branchId;

        $salesorder = $this->salesorderRepository->store($data);

        $result = new SalesOrderResource($salesorder);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("salesorder created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($branchId,$id)
    {
        $salesorder = $this->salesorderRepository->findByMany(['id' => $id, 'branch_id' => $branchId],
        ['approved_by','document','currency','department','client','contact','team','assign_to','quotation','opportunity','salesOrderDetails','salesOrderDetails.item','salesOrderDetails.tax','salesOrderDetails.unit','branch','paymentTerms']);

        if (!$salesorder) {
            throw new NotFoundHttpException();
        }

        $result =  new SalesOrderResource($salesorder);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateSalesOrderRequest $request,$branchId, $id)
    {
        $salesorder = $this->salesorderRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$salesorder) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $salesorder = $this->salesorderRepository->updateByInstance($salesorder, $data);

        $result = new SalesOrderResource($salesorder);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("salesorder updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId,$id)
    {
        $salesorder = $this->salesorderRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$salesorder) {
            throw new NotFoundHttpException();
        }

        $this->salesorderRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("salesorder deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request,$branchId)
    {
        $ids = $request->ids;

        $count = $this->salesorderRepository->destroyAllBy(['branch_id' => $branchId],$ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count salesorders deleted successfully.")
            ->setCode(200);
    }
}
