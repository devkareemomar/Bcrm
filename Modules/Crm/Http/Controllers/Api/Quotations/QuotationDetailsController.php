<?php

namespace Modules\Crm\Http\Controllers\Api\Quotations;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Crm\Http\Requests\Quotations\StoreQuotationDetailsRequest;
use Modules\Crm\Http\Requests\Quotations\UpdateQuotationDetailsRequest;
use Modules\Crm\Http\Resources\ItemDetailsResource;
use Modules\Crm\Repositories\Quotations\QuotationDetailsRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuotationDetailsController extends ApiController
{
    /** inject required classes */
    public function __construct(protected QuotationDetailsRepositoryInterface $quotationDetailsRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:create_crm_quotations')->only('store');
        $this->middleware('permission:update_crm_quotations')->only('update');
        $this->middleware('permission:delete_crm_quotations')->only('destroy', 'bulkDestroy');
    }


    public function store(StoreQuotationDetailsRequest $request)
    {

        $data = $request->validated();
        $data['quotation_id'] = $request->route('quotation_id');

        $quotationDetails =$this->quotationDetailsRepository->store($data);

        $result['item'] = new ItemDetailsResource($quotationDetails['item']);
        $result['master'] = $quotationDetails['master'];

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("quotation details created successfully.")
                    ->setCode(201)
                    ->setResult($result);
    }




    public function update(UpdateQuotationDetailsRequest $request)
    {
        $quotationDetails = $this->quotationDetailsRepository->findById($request->route('detail'));

        if (!$quotationDetails) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $quotationDetails = $this->quotationDetailsRepository->updateByInstance($quotationDetails, $data);

        $result['item']   = new ItemDetailsResource($quotationDetails['item']);
        $result['master'] = $quotationDetails['master'];

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quotation details updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy()
    {
        $id = request()->route('detail');
        $quotationDetails = $this->quotationDetailsRepository->findById($id);

        if (!$quotationDetails) {
            throw new NotFoundHttpException();
        }

        $quotation_id = request()->route('quotation_id');

        $result = $this->quotationDetailsRepository->delete($id,$quotation_id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quotation details deleted successfully.")
            ->setCode(200)
            ->setResult($result);

    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;
        $quotation_id = request()->route('quotation_id');

        $result = $this->quotationDetailsRepository->deleteAll($ids,$quotation_id);
        $count  = $result['count'];
        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count quotation details deleted successfully.")
            ->setCode(200)
            ->setResult($result['master']);

    }
}
