<?php

namespace Modules\Crm\Http\Controllers\Api\Sources;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Modules\Crm\Http\Resources\Sources\SourceResource;
use Modules\Crm\Http\Resources\Sources\SourceCollection;
use Modules\Crm\Http\Requests\Sources\StoreSourceRequest;
use Modules\Crm\Http\Requests\Sources\UpdateSourceRequest;
use Modules\Crm\Http\Resources\Sources\SourceBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\Sources\SourceRepositoryInterface;

class SourceController extends ApiController
{
    /** inject required classes */
    public function __construct(protected SourceRepositoryInterface $sourceRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_sources')->only('index', 'show');
        $this->middleware('permission:create_crm_sources')->only('store');
        $this->middleware('permission:update_crm_sources')->only('update');
        $this->middleware('permission:delete_crm_sources')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $sources = $this->sourceRepository->paginate('id',paginate:Request::query('limit') ?? 25,);

        $result = new SourceCollection($sources);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief()
    {
        $sources = $this->sourceRepository->getAll();

        $result = SourceResource::collection($sources);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreSourceRequest $request)
    {
        $data = $request->validated();
        $source = $this->sourceRepository->store($data);

        $result = new SourceResource($source);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("source created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $source = $this->sourceRepository->findById($id, []);

        if (!$source) {
            throw new NotFoundHttpException();
        }

        $result =  new SourceResource($source);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateSourceRequest $request, $id)
    {
        $source = $this->sourceRepository->findById($id);

        if (!$source) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $source = $this->sourceRepository->updateByInstance($source, $data);

        $result = new SourceResource($source);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("source updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $source = $this->sourceRepository->findById($id);

        if (!$source) {
            throw new NotFoundHttpException();
        }

        $this->sourceRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("source deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->sourceRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count sources deleted successfully.")
            ->setCode(200);
    }
}
