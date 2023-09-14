<?php

namespace Modules\Cms\Http\Controllers\Api\NewsLetters;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\NewsLetters\NewsLetterResource;
use Modules\Cms\Http\Resources\NewsLetters\NewsLetterCollection;
use Modules\Cms\Repositories\NewsLetters\NewsLetterRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsLetterController extends ApiController
{
    /** inject required classes */
    public function __construct(protected NewsLetterRepositoryInterface $newsLetterRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_newsletters')->only('index', 'show');
        $this->middleware('permission:delete_cms_newsletters')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $newsLetters = $this->newsLetterRepository->paginate('id');

        $result = new NewsLetterCollection($newsLetters);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function show($id)
    {
        $newsLetter = $this->newsLetterRepository->findById($id);

        if (!$newsLetter) {
            throw new NotFoundHttpException();
        }

        $result =  new NewsLetterResource($newsLetter);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $newsLetter = $this->newsLetterRepository->findById($id);

        if (!$newsLetter) {
            throw new NotFoundHttpException();
        }

        $this->newsLetterRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("news letter subscriber deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->newsLetterRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count news letters deleted successfully.")
            ->setCode(200);
    }
}
