<?php

namespace Modules\Cms\Http\Controllers\Api\Partners;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Partners\PartnerResource;
use Modules\Cms\Http\Resources\Partners\PartnerCollection;
use Modules\Cms\Http\Requests\Partners\StorePartnerRequest;
use Modules\Cms\Http\Requests\Partners\UpdatePartnerRequest;
use Modules\Cms\Repositories\Partners\PartnerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PartnerController extends ApiController
{
    /** inject required classes */
    public function __construct(protected PartnerRepositoryInterface $partnerRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_partners')->only('index', 'show');
        $this->middleware('permission:create_cms_partners')->only('store');
        $this->middleware('permission:update_cms_partners')->only('update');
        $this->middleware('permission:delete_cms_partners')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $partners = $this->partnerRepository->paginate('id', ['logo', 'category', 'created_by', 'updated_by']);

        $result = new PartnerCollection($partners);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StorePartnerRequest $request)
    {
        $data = $request->validated();

        // add rest of the values
        $data['created_by_id'] = Auth::id();

        $partner = $this->partnerRepository->store($data);

        $result = new PartnerResource($partner);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("partner created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $partner = $this->partnerRepository->findById($id, ['logo', 'category', 'created_by', 'updated_by']);

        if (!$partner) {
            throw new NotFoundHttpException();
        }

        $result =  new PartnerResource($partner);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdatePartnerRequest $request, $id)
    {
        $partner = $this->partnerRepository->findById($id);

        if (!$partner) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        // add rest of the values
        $data['updated_by_id'] = Auth::id();

        $partner = $this->partnerRepository->updateByInstance($partner, $data);

        $result = new PartnerResource($partner);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("partner updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $partner = $this->partnerRepository->findById($id);

        if (!$partner) {
            throw new NotFoundHttpException();
        }

        $this->partnerRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("partner deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->partnerRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count partners deleted successfully.")
            ->setCode(200);
    }
}
