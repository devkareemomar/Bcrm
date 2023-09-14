<?php

namespace Modules\Cms\Http\Controllers\Api\Media;

use Auth;
use App\Services\FileService;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Media\MediaResource;
use Modules\Cms\Http\Resources\Media\MediaCollection;
use Modules\Cms\Http\Requests\Media\StoreMediaRequest;
use Modules\Cms\Http\Requests\Media\UpdateMediaRequest;
use Modules\Cms\Repositories\Media\MediaRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MediaController extends ApiController
{
    /** inject required classes */
    public function __construct(protected MediaRepositoryInterface $mediaRepository)
    {
        /** Roles and Permissions middlewares */
        // $this->middleware('permission:read_cms_media')->only('index', 'show');
        $this->middleware('permission:create_cms_media')->only('store');
        $this->middleware('permission:update_cms_media')->only('update');
        $this->middleware('permission:delete_cms_media')->only('destroy', 'bulkDestroy');
    } 

    public function index()
    {
        $media = $this->mediaRepository->paginate('id', ['created_by', 'updated_by']);

        $result = new MediaCollection($media);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function store(StoreMediaRequest $request, FileService $fileService)
    {
        $data = $request->validated();

        // save file and file metadata
        $data['file']      = $fileService->savePublicFile($request->file, "cms/media", $request->name);
        $data['mime_type'] = $request->file->getMimeType();
        $data['extension'] = empty(($e = $request->file->getClientOriginalExtension())) ? $request->file->extension() : $e;

        // add rest of the values
        $data['created_by_id'] = Auth::id();

        $media = $this->mediaRepository->store($data);

        $result = new MediaResource($media);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("media created successfully.")
            ->setCode(201)
            ->setResult($result);
    }

    public function show($id)
    {
        $media = $this->mediaRepository->findById($id, ['created_by', 'updated_by']);

        if (!$media) {
            throw new NotFoundHttpException();
        }

        $result = new MediaResource($media);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function update(UpdateMediaRequest $request, $id, FileService $fileService)
    {
        $media = $this->mediaRepository->findById($id);

        if (!$media) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        if ($request->name && $request->name != $media->name) {
            $data['file'] = $fileService->renameFile($media->file, $media->name, $request->name);
        }

        // rest of the values
        $data['updated_by_id'] = Auth::id();

        $media = $this->mediaRepository->updateByInstance($media, $data);

        $result = new MediaResource($media);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("media updated successfully.")
            ->setCode(200)
            ->setResult($result);
    }

    public function destroy($id, FileService $fileService)
    {
        $media = $this->mediaRepository->findById($id);

        if (!$media) {
            throw new NotFoundHttpException();
        }

        $fileService->deleteFile($media->file); // delete file

        $this->mediaRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("media deleted successfully.")
            ->setCode(200);
    }

    public function bulkDestroy(BulkDestroyRequest $request, FileService $fileService)
    {
        $ids = $request->ids;

        $paths = $this->mediaRepository->pluckBy('id', $ids, 'file');
        
        $count = $this->mediaRepository->destroyAll($ids);
        
        $fileService->deleteFiles($paths);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count media deleted successfully.")
            ->setCode(200);
    }
}
