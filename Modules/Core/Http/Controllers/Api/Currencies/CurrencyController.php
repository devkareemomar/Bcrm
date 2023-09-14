<?php

namespace Modules\Core\Http\Controllers\Api\Currencies;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\Currencies\CurrencyResource;
use Modules\Core\Http\Resources\Currencies\CurrencyCollection;
use Modules\Core\Http\Requests\Currencies\StoreCurrencyRequest;
use Modules\Core\Http\Requests\Currencies\UpdateCurrencyRequest;
use Modules\Core\Http\Resources\Currencies\CurrencyBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\Currencies\CurrencyRepositoryInterface;

class CurrencyController extends ApiController
{
    /** inject required classes */
    public function __construct(protected CurrencyRepositoryInterface $currencyRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_core_currencies')->only('index', 'show');
        $this->middleware('permission:create_core_currencies')->only('store');
        $this->middleware('permission:update_core_currencies')->only('update');
        $this->middleware('permission:delete_core_currencies')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $currencies = $this->currencyRepository->paginate('id',  paginate: Request::query('limit') ?? 25,);

        $result = new CurrencyCollection($currencies);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief()
    {
        $currencies = $this->currencyRepository->getAll();

        $result = CurrencyBriefResource::collection($currencies);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreCurrencyRequest $request)
    {
        $data = $request->validated();

        $currency = $this->currencyRepository->store($data);

        $result = new CurrencyResource($currency);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("currency created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $currency = $this->currencyRepository->findById($id, []);

        if (!$currency) {
            throw new NotFoundHttpException();
        }

        $result =  new CurrencyResource($currency);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateCurrencyRequest $request, $id)
    {
        $currency = $this->currencyRepository->findById($id);

        if (!$currency) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $currency = $this->currencyRepository->updateByInstance($currency, $data);

        $result = new CurrencyResource($currency);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("currency updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $currency = $this->currencyRepository->findById($id);

        if (!$currency) {
            throw new NotFoundHttpException();
        }

        $this->currencyRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("currency deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->currencyRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count currencies deleted successfully.")
            ->setCode(200);
    }
}
