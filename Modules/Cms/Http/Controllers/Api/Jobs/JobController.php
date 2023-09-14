<?php

namespace Modules\Cms\Http\Controllers\Api\Jobs;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Jobs\JobResource;
use Modules\Cms\Http\Resources\Jobs\JobCollection;
use Modules\Cms\Http\Requests\Jobs\StoreJobRequest;
use Modules\Cms\Http\Requests\Jobs\UpdateJobRequest;
use Modules\Cms\Repositories\Jobs\JobRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JobController extends ApiController
{
    /** inject required classes */
    public function __construct(protected JobRepositoryInterface $jobRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_jobs')->only('index', 'show');
        $this->middleware('permission:create_cms_jobs')->only('store');
        $this->middleware('permission:update_cms_jobs')->only('update');
        $this->middleware('permission:delete_cms_jobs')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $jobs = $this->jobRepository->paginate('id', ['photo']);

        $result = new JobCollection($jobs);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreJobRequest $request)
    {
        $data = $request->validated();

        $job = $this->jobRepository->store($data);

        $result = new JobResource($job);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("job created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $job = $this->jobRepository->findById($id, ['photo']);

        if (!$job) {
            throw new NotFoundHttpException();
        }

        $result =  new JobResource($job);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateJobRequest $request, $id)
    {
        $job = $this->jobRepository->findById($id);

        if (!$job) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $job = $this->jobRepository->updateByInstance($job, $data);

        $result = new JobResource($job);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("job updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $job = $this->jobRepository->findById($id);

        if (!$job) {
            throw new NotFoundHttpException();
        }

        $this->jobRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("job deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->jobRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count jobs deleted successfully.")
            ->setCode(200);
    }
}
