<?php

namespace Modules\Crm\Http\Controllers\Api\Quotations;

use App\Http\Controllers\Api\ApiController;
use Modules\Crm\Http\Requests\Quotations\UpdateStageQuotationRequest;
use Modules\Crm\Http\Resources\Quotations\QuotationResource;
use Modules\Crm\Repositories\Quotations\QuotationRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuotationStatisticController extends ApiController
{
    /** inject required classes */
    public function __construct(protected QuotationRepositoryInterface $quotationRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_quotations')->only('statisticsStage');
    }


    public  function statisticsStage($branchId)
    {
        $result = $this->quotationRepository->statisticsStage($branchId);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function updateStageLead(UpdateStageQuotationRequest $request, $branchId, $id)
    {

        $data = $request->validated();

        $quotation = $this->quotationRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$quotation) {
            throw new NotFoundHttpException();
        }

        $quotation = $this->quotationRepository->updateByInstance($quotation, $data);

        $result = new QuotationResource($quotation);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quotation updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }

}
