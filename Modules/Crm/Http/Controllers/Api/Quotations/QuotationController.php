<?php

namespace Modules\Crm\Http\Controllers\Api\Quotations;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Crm\Http\Resources\Quotations\QuotationResource;
use Modules\Crm\Http\Resources\Quotations\QuotationCollection;
use Modules\Crm\Http\Requests\Quotations\StoreQuotationRequest;
use Modules\Crm\Http\Requests\Quotations\UpdateQuotationRequest;
use Modules\Crm\Http\Resources\Quotations\QuotationBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\Quotations\QuotationRepositoryInterface;

class QuotationController extends ApiController
{
    /** inject required classes */
    public function __construct(protected QuotationRepositoryInterface $quotationRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_quotations')->only('index', 'show');
        $this->middleware('permission:create_crm_quotations')->only('store');
        $this->middleware('permission:update_crm_quotations')->only('update');
        $this->middleware('permission:delete_crm_quotations')->only('destroy', 'bulkDestroy');
    }


    public function index($branchId)
    {
        $quotations = $this->quotationRepository->paginate('id', ['client','contact','team','assign_to'],paginate:Request::query('limit') ?? 25,parameters: ['branch_id' => $branchId]);

        $result = new QuotationCollection($quotations);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief($branchId)
    {
        $quotations = $this->quotationRepository->getAll(parameters: ['branch_id' => $branchId]);

        $result = QuotationBriefResource::collection($quotations);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreQuotationRequest $request,$branchId)
    {
        $data = $request->validated();
        $data['branch_id'] = $branchId;

        $quotation = $this->quotationRepository->store($data);

        $result = new QuotationResource($quotation);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quotation created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($branchId,$id)
    {
        $quotation = $this->quotationRepository->findByMany(['id' => $id, 'branch_id' => $branchId], ['client','contact','currency','department','quotationDetails','quotationDetails.item','quotationDetails.tax','quotationDetails.unit','team','assign_to','paymentTerms']);

        if (!$quotation) {
            throw new NotFoundHttpException();
        }

        $result =  new QuotationResource($quotation);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateQuotationRequest $request,$branchId, $id)
    {
        $quotation = $this->quotationRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$quotation) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $quotation = $this->quotationRepository->updateByInstance($quotation, $data);

        $result = new QuotationResource($quotation);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quotation updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId,$id)
    {
        $quotation = $this->quotationRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$quotation) {
            throw new NotFoundHttpException();
        }

        $this->quotationRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quotation deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request,$branchId)
    {
        $ids = $request->ids;

        $count = $this->quotationRepository->destroyAllBy(['branch_id' => $branchId],$ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count quotations deleted successfully.")
            ->setCode(200);
    }
}
