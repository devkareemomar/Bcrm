<?php

namespace Modules\Cms\Http\Controllers\Api\Products;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Products\ProductPhotoResource;
use Modules\Cms\Http\Requests\Products\StoreProductPhotoRequest;
use Modules\Cms\Repositories\Products\ProductRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Modules\Cms\Repositories\Products\ProductPhotoRepositoryInterface;

class ProductPhotoController extends ApiController
{
    /** inject required classes */
    public function __construct(
        protected ProductPhotoRepositoryInterface $productPhotoRepository,
        protected ProductRepositoryInterface $productRepository
    ) {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_products')->only('index', 'show');
        $this->middleware('permission:create_cms_products')->only('store');
        $this->middleware('permission:update_cms_products')->only('update');
        $this->middleware('permission:delete_cms_products')->only('destroy', 'bulkDestroy');
    }


    public function index($id)
    {
        $productPhotos = $this->productPhotoRepository->paginate('id', ['photo', 'created_by', 'updated_by'], [], 25, ['product_id' => $id]);

        $result = ProductPhotoResource::collection($productPhotos);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreProductPhotoRequest $request, $id)
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new NotFoundHttpException();
        }

        foreach ($request->photos_media_ids as $id) {
            $data['photo_media_id'] = $id;
            $data['created_by_id'] = Auth::id();
            $data['product_id'] = $product->id;
            $this->productPhotoRepository->store($data);
        }

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("product photos created successfully.")
                    ->setCode(201);
    }


    public function show($productId, $photoId)
    {
        $productPhoto = $this->productPhotoRepository->findByMany(
            ['id' => $photoId, 'product_id' => $productId],
            ['photo', 'created_by', 'updated_by']
        );

        if (!$productPhoto) {
            throw new NotFoundHttpException();
        }

        $result =  new ProductPhotoResource($productPhoto);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function destroy($productId, $photoId)
    {
        $productPhoto = $this->productPhotoRepository->findByMany(
            ['id' => $photoId, 'product_id' => $productId]
        );

        if (!$productPhoto) {
            throw new NotFoundHttpException();
        }

        $this->productPhotoRepository->destroy($photoId);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("product photo deleted successfully.")
            ->setCode(200);
    }


    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->productPhotoRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count photos deleted successfully.")
            ->setCode(200);
    }
}
