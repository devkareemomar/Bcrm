<?php

namespace Modules\Inventory\Http\Controllers\Api\Taxes;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Inventory\Http\Resources\Taxes\TaxResource;
use Modules\Inventory\Http\Resources\Taxes\TaxCollection;
use Modules\Inventory\Http\Requests\Taxes\StoreTaxRequest;
use Modules\Inventory\Http\Requests\Taxes\UpdateTaxRequest;
use Modules\Inventory\Http\Resources\Taxes\TaxBriefResource;
use Modules\Inventory\Repositories\Taxes\TaxRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxController extends ApiController
{
    /** inject required classes */
    public function __construct(protected TaxRepositoryInterface $taxRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_inventory_taxes')->only('index', 'show');
        $this->middleware('permission:create_inventory_taxes')->only('store');
        $this->middleware('permission:update_inventory_taxes')->only('update');
        $this->middleware('permission:delete_inventory_taxes')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $taxes = $this->taxRepository->paginate('id');

        $result = new TaxCollection($taxes);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief()
    {
        $taxes = $this->taxRepository->getAll();

        $result = TaxBriefResource::collection($taxes);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreTaxRequest $request)
    {
        $data = $request->validated();

        $tax = $this->taxRepository->store($data);

        $result = new TaxResource($tax);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("tax created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $tax = $this->taxRepository->findById($id);

        if (!$tax) {
            throw new NotFoundHttpException();
        }

        $result =  new TaxResource($tax);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateTaxRequest $request, $id)
    {
        $tax = $this->taxRepository->findById($id);

        if (!$tax) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $tax = $this->taxRepository->updateByInstance($tax, $data);

        $result = new TaxResource($tax);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("tax updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $tax = $this->taxRepository->findById($id);

        if (!$tax) {
            throw new NotFoundHttpException();
        }

        $this->taxRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("tax deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->taxRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count taxes deleted successfully.")
            ->setCode(200);
    }
}
