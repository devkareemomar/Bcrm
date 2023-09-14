<?php

namespace Modules\Cms\Http\Controllers\Api\Messages;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Messages\MessageResource;
use Modules\Cms\Http\Resources\Messages\MessageCollection;
use Modules\Cms\Repositories\Messages\MessageRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageController extends ApiController
{
    /** inject required classes */
    public function __construct(protected MessageRepositoryInterface $messageRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_messages')->only('index', 'show');
        $this->middleware('permission:delete_cms_messages')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $messages = $this->messageRepository->paginate('id');

        $result = new MessageCollection($messages);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function show($id)
    {
        $message = $this->messageRepository->findById($id);

        if (!$message) {
            throw new NotFoundHttpException();
        }

        $result =  new MessageResource($message);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id)
    {
        $message = $this->messageRepository->findById($id);

        if (!$message) {
            throw new NotFoundHttpException();
        }

        $this->messageRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("message deleted successfully.")
            ->setCode(200);
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->messageRepository->destroyAll($ids);


        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count messages deleted successfully.")
            ->setCode(200);
    }
}
