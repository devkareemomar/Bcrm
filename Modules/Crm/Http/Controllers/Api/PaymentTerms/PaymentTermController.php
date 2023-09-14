<?php

namespace Modules\Crm\Http\Controllers\Api\PaymentTerms;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Crm\Enums\PaymentableTypeEnum;
use Modules\Crm\Http\Requests\PaymentTerms\StoreBulkPaymentTermRequest;
use Modules\Crm\Http\Resources\PaymentTerms\PaymentTermResource;
use Modules\Crm\Http\Resources\PaymentTerms\PaymentTermCollection;
use Modules\Crm\Http\Requests\PaymentTerms\StorePaymentTermRequest;
use Modules\Crm\Http\Requests\PaymentTerms\UpdatePaymentTermRequest;
use Modules\Crm\Http\Resources\PaymentTerms\PaymentTermBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\PaymentTerms\PaymentTermRepositoryInterface;

class PaymentTermController extends ApiController
{
    /** inject required classes */
    public function __construct(protected PaymentTermRepositoryInterface $paymenttermRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_paymentterms')->only('index', 'show');
        $this->middleware('permission:create_crm_paymentterms')->only('store');
        $this->middleware('permission:update_crm_paymentterms')->only('update');
        $this->middleware('permission:delete_crm_paymentterms')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $paymentterms = $this->paymenttermRepository->paginate('id',paginate:Request::query('limit') ?? 25,);

        $result = new PaymentTermCollection($paymentterms);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief()
    {
        $paymentterms = $this->paymenttermRepository->getAll();

        $result = PaymentTermBriefResource::collection($paymentterms);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StorePaymentTermRequest $request)
    {
        $data = $request->validated();
        $data['paymentable_type'] = 'Modules\Crm\Models\\' . $data['paymentable_type'];

        $paymentterm = $this->paymenttermRepository->store($data);

        $result = new PaymentTermResource($paymentterm);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("payment terms created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function bulkStore(StoreBulkPaymentTermRequest $request)
    {
        $data = $request->validated();
        $this->paymenttermRepository->bulkStore($data);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("payment terms created successfully.")
            ->setCode(201);
    }

    public function show($id)
    {
        $paymentterm = $this->paymenttermRepository->findById($id, []);

        if (!$paymentterm) {
            throw new NotFoundHttpException();
        }

        $result =  new PaymentTermResource($paymentterm);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdatePaymentTermRequest $request, $id)
    {
        $paymentterm = $this->paymenttermRepository->findById($id);

        if (!$paymentterm) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();
        $data['paymentable_type'] = 'Modules\Crm\Models\\' . $data['paymentable_type'];

        $paymentterm = $this->paymenttermRepository->updateByInstance($paymentterm, $data);

        $result = new PaymentTermResource($paymentterm);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("payment terms updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $paymentterm = $this->paymenttermRepository->findById($id);

        if (!$paymentterm) {
            throw new NotFoundHttpException();
        }

        $this->paymenttermRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("payment terms deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->paymenttermRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count payment terms deleted successfully.")
            ->setCode(200);
    }

    public function PaymentType()
    {
        $result =PaymentableTypeEnum::values();

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);

    }
}
