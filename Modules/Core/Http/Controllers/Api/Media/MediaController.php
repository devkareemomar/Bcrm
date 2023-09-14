<?php

namespace Modules\Core\Http\Controllers\Api\Media;


use App\Services\FileService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Illuminate\Support\Facades\Request;
use Modules\Core\Http\Resources\Media\MediaResource;
use Modules\Core\Http\Resources\Media\MediaCollection;
use Modules\Core\Http\Requests\Media\StoreMediaRequest;
use Modules\Core\Repositories\Media\MediaRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MediaController extends ApiController
{
    /** inject required classes */
    public function __construct(protected MediaRepositoryInterface $mediaRepository)
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:create_core_media')->only('store');
        $this->middleware('permission:delete_core_media')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $media = $this->mediaRepository->paginate('id',  paginate: Request::query('limit') ?? 25,);

        $result = new MediaCollection($media);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }

    public function store(StoreMediaRequest $request, FileService $fileService)
    {
        $data = $request->validated();

        // save file and file metadata
        $data['file']      = $fileService->savePublicFile($request->file, "core/media");
        $data['mime_type'] = $request->file->getMimeType();
        $data['extension'] = empty(($e = $request->file->getClientOriginalExtension())) ? $request->file->extension() : $e;

        // dd($data);
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
