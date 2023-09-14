<?php

namespace Modules\Crm\Http\Controllers\Api\SalesInvoices;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Crm\Http\Resources\SalesInvoices\SalesInvoiceResource;
use Modules\Crm\Http\Resources\SalesInvoices\SalesInvoiceCollection;
use Modules\Crm\Http\Requests\SalesInvoices\StoreSalesInvoiceRequest;
use Modules\Crm\Http\Requests\SalesInvoices\UpdateSalesInvoiceRequest;
use Modules\Crm\Http\Resources\SalesInvoices\SalesInvoiceBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\SalesInvoices\SalesInvoiceRepositoryInterface;

class SalesInvoiceController extends ApiController
{
    /** inject required classes */
    public function __construct(protected SalesInvoiceRepositoryInterface $salesinvoiceRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_salesinvoices')->only('index', 'show');
        $this->middleware('permission:create_crm_salesinvoices')->only('store');
        $this->middleware('permission:update_crm_salesinvoices')->only('update');
        $this->middleware('permission:delete_crm_salesinvoices')->only('destroy', 'bulkDestroy');
    }


    public function index($branchId)
    {
        $salesinvoices = $this->salesinvoiceRepository->paginate('id', paginate:Request::query('limit') ?? 25,parameters: ['branch_id' => $branchId]);

        $result = new SalesInvoiceCollection($salesinvoices);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($branchId)
    {
        $salesinvoices = $this->salesinvoiceRepository->getAll(parameters: ['branch_id' => $branchId]);

        $result = SalesInvoiceBriefResource::collection($salesinvoices);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreSalesInvoiceRequest $request, $branchId)
    {
        $data = $request->validated();
        $data['branch_id'] = $branchId;

        $salesinvoice = $this->salesinvoiceRepository->store($data);

        $result = new SalesInvoiceResource($salesinvoice);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("salesinvoice created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($branchId, $id)
    {
        $salesinvoice = $this->salesinvoiceRepository->findByMany(['id' => $id, 'branch_id' => $branchId],
        ['approved_by', 'document', 'currency', 'department', 'client','contact','team','assign_to', 'quotation', 'opportunity', 'salesOrder', 'salesInvoiceDetails','salesInvoiceDetails.item','salesInvoiceDetails.tax','salesInvoiceDetails.unit']);

        if (!$salesinvoice) {
            throw new NotFoundHttpException();
        }

        $result =  new SalesInvoiceResource($salesinvoice);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateSalesInvoiceRequest $request, $branchId, $id)
    {
        $salesinvoice = $this->salesinvoiceRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$salesinvoice) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $salesinvoice = $this->salesinvoiceRepository->updateByInstance($salesinvoice, $data);

        $result = new SalesInvoiceResource($salesinvoice);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("salesinvoice updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId, $id)
    {
        $salesinvoice = $this->salesinvoiceRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$salesinvoice) {
            throw new NotFoundHttpException();
        }

        $this->salesinvoiceRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("salesinvoice deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, $branchId)
    {
        $ids = $request->ids;

        $count = $this->salesinvoiceRepository->destroyAllBy(['branch_id' => $branchId],$ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count salesinvoices deleted successfully.")
            ->setCode(200);
    }
}
