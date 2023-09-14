<?php

namespace Modules\Core\Http\Controllers\Api\States;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\States\StateResource;
use Modules\Core\Http\Resources\States\StateCollection;
use Modules\Core\Http\Requests\States\StoreStateRequest;
use Modules\Core\Http\Requests\States\UpdateStateRequest;
use Modules\Core\Http\Resources\States\StateBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\States\StateRepositoryInterface;

class StateController extends ApiController
{
    /** inject required classes */
    public function __construct(protected StateRepositoryInterface $stateRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_core_states')->only('index', 'show');
        $this->middleware('permission:create_core_states')->only('store');
        $this->middleware('permission:update_core_states')->only('update');
        $this->middleware('permission:delete_core_states')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $states = $this->stateRepository->paginate('id', ['country'], paginate: Request::query('limit') ?? 25,);

        $result = new StateCollection($states);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief()
    {
        $states = $this->stateRepository->getAll(fields: ['id', 'name']);

        $result = StateBriefResource::collection($states);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreStateRequest $request)
    {
        $data = $request->validated();

        $state = $this->stateRepository->store($data);

        $result = new StateResource($state);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("state created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $state = $this->stateRepository->findById($id, ['country']);

        if (!$state) {
            throw new NotFoundHttpException();
        }

        $result =  new StateResource($state);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateStateRequest $request, $id)
    {
        $state = $this->stateRepository->findById($id);

        if (!$state) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $state = $this->stateRepository->updateByInstance($state, $data);

        $result = new StateResource($state);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("state updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $state = $this->stateRepository->findById($id);

        if (!$state) {
            throw new NotFoundHttpException();
        }

        $this->stateRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("state deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->stateRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count states deleted successfully.")
            ->setCode(200);
    }
}
