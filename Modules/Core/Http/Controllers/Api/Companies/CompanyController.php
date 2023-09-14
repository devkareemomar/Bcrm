<?php

namespace Modules\Core\Http\Controllers\Api\Companies;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\Companies\CompanyResource;
use Modules\Core\Http\Resources\Companies\CompanyCollection;
use Modules\Core\Http\Requests\Companies\StoreCompanyRequest;
use Modules\Core\Http\Requests\Companies\UpdateCompanyRequest;
use Modules\Core\Http\Resources\Companies\CompanyBranchesBriefResource;
use Modules\Core\Http\Resources\Companies\CompanyBriefResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Core\Repositories\Companies\CompanyRepositoryInterface;

class CompanyController extends ApiController
{
    /** inject required classes */
    public function __construct(protected CompanyRepositoryInterface $companyRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('role:super_admin')->except('brief');
    }


    public function index()
    {
        $companies = $this->companyRepository->paginate('id', paginate: Request::query('limit') ?? 25, fields: ['id', 'name', 'address', 'phone', 'email', 'website']);

        $result = new CompanyCollection($companies);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function brief()
    {
        $companies = $this->companyRepository->getAll(fields: ['id', 'name']);

        $result = CompanyBriefResource::collection($companies);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function briefCompanyBranches()
    {
        $companies = $this->companyRepository->getAll(relations:['branches'],fields: ['id', 'name']);
        $result = CompanyBranchesBriefResource::collection($companies);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();

        $company = $this->companyRepository->store($data);

        $result = new CompanyResource($company);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("company created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $company = $this->companyRepository->findById($id, ['logo', 'header', 'footer', 'stamp', 'signature', 'document']);

        if (!$company) {
            throw new NotFoundHttpException();
        }

        $result =  new CompanyResource($company);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateCompanyRequest $request, $id)
    {
        $company = $this->companyRepository->findById($id);

        if (!$company) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $company = $this->companyRepository->updateByInstance($company, $data);

        $result = new CompanyResource($company);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("company updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $company = $this->companyRepository->findById($id);

        if (!$company) {
            throw new NotFoundHttpException();
        }

        $this->companyRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("company deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->companyRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count companies deleted successfully.")
            ->setCode(200);
    }
}
