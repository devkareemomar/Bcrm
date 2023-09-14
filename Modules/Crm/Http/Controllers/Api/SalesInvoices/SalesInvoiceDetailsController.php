<?php

namespace Modules\Crm\Http\Controllers\Api\SalesInvoices;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Crm\Http\Requests\SalesInvoices\StoreSalesInvoiceDetailsRequest;
use Modules\Crm\Http\Requests\SalesInvoices\UpdateSalesInvoiceDetailsRequest;
use Modules\Crm\Http\Resources\ItemDetailsResource;
use Modules\Crm\Repositories\SalesInvoices\SalesInvoiceDetailsRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SalesInvoiceDetailsController extends ApiController
{
    /** inject required classes */
    public function __construct(protected SalesInvoiceDetailsRepositoryInterface $salesInvoiceDetailsRepository)
    {

        /** Roles and Permissions middlewares */
        $this->middleware('permission:create_crm_salesinvoices')->only('store');
        $this->middleware('permission:update_crm_salesinvoices')->only('update');
        $this->middleware('permission:delete_crm_salesinvoices')->only('destroy', 'bulkDestroy');
    }


    public function store(StoreSalesInvoiceDetailsRequest $request)
    {

        $data = $request->validated();

        $data['sales_invoice_id'] = $request->route('sales_invoice_id');

        $salesInvoiceDetails =$this->salesInvoiceDetailsRepository->store($data);

        $result['item'] = new ItemDetailsResource($salesInvoiceDetails['item']);
        $result['master'] = $salesInvoiceDetails['master'];

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("sales invoice details created successfully.")
                    ->setCode(201)
                    ->setResult($result);
    }


    public function show($id)
    {
        $salesInvoiceDetails = $this->salesInvoiceDetailsRepository->findById($id, ['item', 'tax']);

        if (!$salesInvoiceDetails) {
            throw new NotFoundHttpException();
        }

        $result =  new ItemDetailsResource($salesInvoiceDetails);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateSalesInvoiceDetailsRequest $request)
    {
        $salesInvoiceDetails = $this->salesInvoiceDetailsRepository->findById($request->route('detail'));

        if (!$salesInvoiceDetails) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $salesInvoiceDetails = $this->salesInvoiceDetailsRepository->updateByInstance($salesInvoiceDetails, $data);

        $result['item']   = new ItemDetailsResource($salesInvoiceDetails['item']);
        $result['master'] = $salesInvoiceDetails['master'];

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("sales invoice details updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy()
    {
        $id = request()->route('detail');
        $salesInvoiceDetails = $this->salesInvoiceDetailsRepository->findById($id);

        if (!$salesInvoiceDetails) {
            throw new NotFoundHttpException();
        }

        $sales_invoice = request()->route('sales_order_id');
        $this->salesInvoiceDetailsRepository->delete($id,$sales_invoice);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("sales invoice details deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;
        $sales_invoice = request()->route('sales_order_id');

        $result = $this->salesInvoiceDetailsRepository->deleteAll($ids,$sales_invoice);
        $count  = $result['count'];

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count sales invoice details deleted successfully.")
            ->setCode(200);
    }
}
