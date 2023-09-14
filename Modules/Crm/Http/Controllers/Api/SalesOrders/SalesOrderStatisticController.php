<?php

namespace Modules\Crm\Http\Controllers\Api\SalesOrders;

use App\Http\Controllers\Api\ApiController;
use Modules\Crm\Http\Requests\SalesOrders\UpdateStageSalesOrderRequest;
use Modules\Crm\Http\Resources\Quotations\QuotationResource;
use Modules\Crm\Repositories\SalesOrders\SalesOrderRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SalesOrderStatisticController extends ApiController
{
    /** inject required classes */
    public function __construct(protected SalesOrderRepositoryInterface $salesorderRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_salesorders')->only('statisticsStage');
    }


    public  function statisticsStage($branchId)
    {
        $result = $this->salesorderRepository->statisticsStage($branchId);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function updateStageLead(UpdateStageSalesOrderRequest $request, $branchId, $id)
    {

        $data = $request->validated();

        $quotation = $this->salesorderRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$quotation) {
            throw new NotFoundHttpException();
        }

        $quotation = $this->salesorderRepository->updateByInstance($quotation, $data);

        $result = new QuotationResource($quotation);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("quotation updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }

}
