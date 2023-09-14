<?php

namespace Modules\Cms\Http\Controllers\Api\Posts;

use Auth;
use App\Services\FileService;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Posts\PostResource;
use Modules\Cms\Http\Resources\Posts\PostCollection;
use Modules\Cms\Http\Requests\Posts\StorePostRequest;
use Modules\Cms\Http\Requests\Posts\UpdatePostRequest;
use Modules\Cms\Repositories\Posts\PostRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends ApiController
{
    /** inject required classes */
    public function __construct(protected PostRepositoryInterface $postRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_posts')->only('index', 'show');
        $this->middleware('permission:create_cms_posts')->only('store');
        $this->middleware('permission:update_cms_posts')->only('update');
        $this->middleware('permission:delete_cms_posts')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $posts = $this->postRepository->paginate('id', ['category', 'created_by', 'updated_by'], ['comments']);

        $result = new PostCollection($posts);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StorePostRequest $request, FileService $fileService)
    {
        $data = $request->validated();

        // save photo
        if ($request->photo) {
            $data['photo'] = $fileService->savePublicFile($request->photo, "cms/posts");
        }

        // add rest of the values
        $data['created_by_id'] = Auth::id();

        $post = $this->postRepository->store($data);

        $result = new PostResource($post);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("post created successfully.")
            ->setCode(201)
            ->setResult($result);
    }


    public function show($id)
    {
        $post = $this->postRepository->findById($id, ['category', 'created_by', 'updated_by']);

        if (!$post) {
            throw new NotFoundHttpException();
        }

        $result =  new PostResource($post);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdatePostRequest $request, $id, FileService $fileService)
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        // save photo
        if ($request->photo) {
            $data['photo'] = $fileService->savePublicFile($request->photo, "cms/posts");
            $fileService->deleteFile($post->photo); // delete old photo
        }

        // add rest of the values
        $data['updated_by_id'] = Auth::id();

        $post = $this->postRepository->updateByInstance($post, $data);

        $result = new PostResource($post);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("post updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($id, FileService $fileService)
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new NotFoundHttpException();
        }

        $fileService->deleteFile($post->photo); // delete photo
        $this->postRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("post deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request, FileService $fileService)
    {
        $ids = $request->ids;

        $paths = $this->postRepository->pluckBy('id', $ids, 'photo');

        $count = $this->postRepository->destroyAll($ids);

        $fileService->deleteFiles($paths);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count posts deleted successfully.")
            ->setCode(200);
    }
}
