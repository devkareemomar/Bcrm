<?php

namespace Modules\Crm\Http\Controllers\Api\Clients;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Crm\Http\Resources\Clients\ClientResource;
use Modules\Crm\Http\Resources\Clients\ClientCollection;
use Modules\Crm\Http\Requests\Clients\StoreClientRequest;
use Modules\Crm\Http\Requests\Clients\UpdateClientRequest;
use Modules\Crm\Http\Resources\Clients\ClientBriefResource;
use Modules\Crm\Http\Resources\Contacts\ContactBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\Clients\ClientRepositoryInterface;

class ClientController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ClientRepositoryInterface $clientRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_clients')->only('index', 'show');
        $this->middleware('permission:create_crm_clients')->only('store');
        $this->middleware('permission:update_crm_clients')->only('update');
        $this->middleware('permission:delete_crm_clients')->only('destroy', 'bulkDestroy');
    }


    public function index($branchId)
    {
        $clients = $this->clientRepository->paginate('id',paginate:Request::query('limit') ?? 25, parameters: ['branch_id' => $branchId]);
        $result = new ClientCollection($clients);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief($branchId)
    {
        $clients = $this->clientRepository->getAll(parameters: ['branch_id' => $branchId]);

        $result = ClientBriefResource::collection($clients);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreClientRequest $request,$branchId)
    {
        $data = $request->validated();

        $client = $this->clientRepository->store($data);

        $result = new ClientResource($client);

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("client created successfully.")
                    ->setCode(201)
                    ->setResult($result);
    }


    public function show($branchId,$id)
    {
        $client = $this->clientRepository->findByMany(['id' => $id, 'branch_id' => $branchId], ['documents','source','photo','branch']);

        if (!$client) {
            throw new NotFoundHttpException();
        }

        $result =  new ClientResource($client);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function update(UpdateClientRequest $request,$branchId, $id)
    {
        $client = $this->clientRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$client) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $client = $this->clientRepository->updateByInstance($client, $data);

        $result = new ClientResource($client);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("client updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId,$id)
    {
        $client = $this->clientRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$client) {
            throw new NotFoundHttpException();
        }

        $this->clientRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("client deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request,$branchId)
    {
        $ids = $request->ids;

        $count = $this->clientRepository->destroyAllBy(['branch_id' => $branchId], $ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count clients deleted successfully.")
            ->setCode(200);
    }

    public function contactByClient($client_id)
    {
        $client = $this->clientRepository->findById($client_id,['contacts']);

        if (!$client) {
            throw new NotFoundHttpException();
        }

        $contacts = $client->contacts;

        $result =  ContactBriefResource::collection($contacts);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

}
