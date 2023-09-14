<?php

namespace Modules\Crm\Http\Controllers\Api\Contacts;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Crm\Enums\ContactableTypeEnum;
use Modules\Crm\Http\Resources\Contacts\ContactResource;
use Modules\Crm\Http\Resources\Contacts\ContactCollection;
use Modules\Crm\Http\Requests\Contacts\StoreContactRequest;
use Modules\Crm\Http\Requests\Contacts\UpdateContactRequest;
use Modules\Crm\Http\Resources\Contacts\ContactBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Crm\Repositories\Contacts\ContactRepositoryInterface;

class ContactController extends ApiController
{
    /** inject required classes */
    public function __construct(protected ContactRepositoryInterface $contactRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_crm_contacts')->only('index', 'show');
        $this->middleware('permission:create_crm_contacts')->only('store');
        $this->middleware('permission:update_crm_contacts')->only('update');
        $this->middleware('permission:delete_crm_contacts')->only('destroy', 'bulkDestroy');
    }


    public function index($branchId)
    {
        $contacts = $this->contactRepository->paginate('id',paginate:Request::query('limit') ?? 25, parameters: ['branch_id' => $branchId]);
        $result = new ContactCollection($contacts);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }


    public function brief($branchId)
    {
        $contacts = $this->contactRepository->getAll(parameters: ['branch_id' => $branchId]);

        $result = ContactBriefResource::collection($contacts);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreContactRequest $request,$branchId)
    {

        $data = $request->validated();
        $data['branch_id'] = $branchId;

        // TOFIX:
        $id = $this->contactRepository->maxId();
        $data['code'] = 'Contact_' . $id;
        $contact = $this->contactRepository->store($data);

        $result = new ContactResource($contact);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("contact created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($branchId,$id)
    {
        $contact = $this->contactRepository->findByMany(['id' => $id, 'branch_id' => $branchId], ['city','country','photo','client','lead']);

        if (!$contact) {
            throw new NotFoundHttpException();
        }

        $result =  new ContactResource($contact);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateContactRequest $request,$branchId, $id)
    {
        $contact = $this->contactRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$contact) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $contact = $this->contactRepository->updateByInstance($contact, $data);

        $result = new ContactResource($contact);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("contact updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($branchId,$id)
    {
        $contact = $this->contactRepository->findByMany(['id' => $id, 'branch_id' => $branchId]);

        if (!$contact) {
            throw new NotFoundHttpException();
        }

        $this->contactRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("contact deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request,$branchId)
    {
        $ids = $request->ids;

        $count = $this->contactRepository->destroyAllBy(['branch_id' => $branchId],$ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count contacts deleted successfully.")
            ->setCode(200);
    }

    public function contactType()
    {
        $result =ContactableTypeEnum::values();

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);

    }


    public function contactsByClient($branchId,$client_id)
    {
        $contacts = $this->contactRepository->getAll(parameters: ['branch_id' => $branchId,'client_id'=>$client_id]);

        $result = ContactBriefResource::collection($contacts);

        return $this->jsonResponse()->setStatus(true)
                    ->setCode(200)
                    ->setResult($result);
    }
}
