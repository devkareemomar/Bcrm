<?php

namespace Modules\Cms\Http\Controllers\Api\Faqs;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Faqs\FaqResource;
use Modules\Cms\Http\Resources\Faqs\FaqCollection;
use Modules\Cms\Http\Requests\Faqs\StoreFaqRequest;
use Modules\Cms\Http\Requests\Faqs\UpdateFaqRequest;
use Modules\Cms\Repositories\Faqs\FaqRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FaqController extends ApiController
{
    /** inject required classes */
    public function __construct(protected FaqRepositoryInterface $faqRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_faqs')->only('index', 'show');
        $this->middleware('permission:create_cms_faqs')->only('store');
        $this->middleware('permission:update_cms_faqs')->only('update');
        $this->middleware('permission:delete_cms_faqs')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $faqs = $this->faqRepository->paginate('id', ['photo', 'created_by', 'updated_by']);

        $result = new FaqCollection($faqs);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreFaqRequest $request)
    {
        $data = $request->validated();

        // add rest of the values
        $data['created_by_id'] = Auth::id();

        $faq = $this->faqRepository->store($data);

        $result = new FaqResource($faq);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("faq created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $faq = $this->faqRepository->findById($id, ['photo', 'created_by', 'updated_by']);

        if (!$faq) {
            throw new NotFoundHttpException();
        }

        $result =  new FaqResource($faq);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateFaqRequest $request, $id)
    {
        $faq = $this->faqRepository->findById($id);

        if (!$faq) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        // add rest of the values
        $data['updated_by_id'] = Auth::id();

        $faq = $this->faqRepository->updateByInstance($faq, $data);

        $result = new FaqResource($faq);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("faq updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $faq = $this->faqRepository->findById($id);

        if (!$faq) {
            throw new NotFoundHttpException();
        }

        $this->faqRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("faq deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->faqRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count faqs deleted successfully.")
            ->setCode(200);
    }
}
