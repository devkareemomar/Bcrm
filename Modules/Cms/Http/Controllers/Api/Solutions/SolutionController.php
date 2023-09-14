<?php

namespace Modules\Cms\Http\Controllers\Api\Solutions;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Solutions\SolutionResource;
use Modules\Cms\Http\Resources\Solutions\SolutionCollection;
use Modules\Cms\Http\Requests\Solutions\StoreSolutionRequest;
use Modules\Cms\Http\Requests\Solutions\UpdateSolutionRequest;
use Modules\Cms\Http\Resources\Solutions\BriefSolutionResource;
use Modules\Cms\Repositories\Solutions\SolutionRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SolutionController extends ApiController
{
    /** inject required classes */
    public function __construct(protected SolutionRepositoryInterface $solutionRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_solutions')->only('index', 'show');
        $this->middleware('permission:create_cms_solutions')->only('store');
        $this->middleware('permission:update_cms_solutions')->only('update');
        $this->middleware('permission:delete_cms_solutions')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $solutions = $this->solutionRepository->paginate('id', ['icon', 'photo', 'created_by', 'updated_by'], [], 25, [], [
            'id', 'title_ar', 'title_en', 'description_ar', 'description_en', 'created_at', 'updated_at', 'icon_media_id', 'photo_media_id','created_by_id', 'updated_by_id'
        ]);

        $result = new SolutionCollection($solutions);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function brief()
    {
        $solutions = $this->solutionRepository->getAll();

        $result = BriefSolutionResource::collection($solutions);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function store(StoreSolutionRequest $request)
    {
        $data = $request->validated();

        $data['created_by_id'] = Auth::id();

        $solution = $this->solutionRepository->store($data);

        $result = new SolutionResource($solution);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("solution created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $solution = $this->solutionRepository->findById($id, ['icon', 'photo', 'created_by', 'updated_by']);

        if (!$solution) {
            throw new NotFoundHttpException();
        }

        $result =  new SolutionResource($solution);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateSolutionRequest $request, $id)
    {
        $solution = $this->solutionRepository->findById($id);

        if (!$solution) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        $data['updated_by_id'] = Auth::id();

        $solution = $this->solutionRepository->updateByInstance($solution, $data);

        $result = new solutionResource($solution);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("solution updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $solution = $this->solutionRepository->findById($id);

        if (!$solution) {
            throw new NotFoundHttpException();
        }

        $this->solutionRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("solution deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->solutionRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count solutions deleted successfully.")
            ->setCode(200);
    }
}
