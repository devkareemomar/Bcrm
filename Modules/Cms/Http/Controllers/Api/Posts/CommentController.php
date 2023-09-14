<?php

namespace Modules\Cms\Http\Controllers\Api\Posts;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Posts\CommentResource;
use Modules\Cms\Http\Resources\Posts\CommentCollection;
use Modules\Cms\Repositories\Posts\CommentRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends ApiController
{
    /** inject required classes */
    public function __construct(protected CommentRepositoryInterface $commentRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_comments')->only('index', 'show', 'indexByPost');
        $this->middleware('permission:delete_cms_comments')->only('destroy', 'bulkDestroy');
    }


    public function index($post_id)
    {
        $comments = $this->commentRepository->paginate('id', ['user'], [], 25, ['post_id' => $post_id]);

        $result = new CommentCollection($comments);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function show($id)
    {
        $comment = $this->commentRepository->findById($id, ['user']);

        if (!$comment) {
            throw new NotFoundHttpException();
        }

        $result =  new CommentResource($comment);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function destroy($id)
    {
        $comment = $this->commentRepository->findById($id);

        if (!$comment) {
            throw new NotFoundHttpException();
        }

        $this->commentRepository->destroy($id);
        
        return $this->jsonResponse()->setStatus(true)
            ->setMessage("comment deleted successfully.")
            ->setCode(200);
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->commentRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count comments deleted successfully.")
            ->setCode(200);
    }
}
