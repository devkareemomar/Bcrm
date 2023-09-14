<?php

namespace Modules\Cms\Http\Controllers\Api\Products;

use Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\BulkDestroyRequest;
use Modules\Cms\Http\Resources\Products\ProductResource;
use Modules\Cms\Http\Resources\Products\ProductCollection;
use Modules\Cms\Http\Requests\Products\StoreProductRequest;
use Modules\Cms\Http\Requests\Products\UpdateProductRequest;
use Modules\Cms\Repositories\Products\ProductPhotoRepositoryInterface;
use Modules\Cms\Repositories\Products\ProductRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends ApiController
{
    /** inject required classes */
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected ProductPhotoRepositoryInterface $productPhotoRepository
        )
    {
        /** Roles and Permissions middlewares */
        $this->middleware('permission:read_cms_products')->only('index', 'show');
        $this->middleware('permission:create_cms_products')->only('store');
        $this->middleware('permission:update_cms_products')->only('update');
        $this->middleware('permission:delete_cms_products')->only('destroy', 'bulkDestroy');
    }


    public function index()
    {
        $products = $this->productRepository->paginate('id', ['photo', 'category', 'created_by', 'updated_by']);

        $result = new ProductCollection($products);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // add rest of the values
        $data['created_by_id'] = Auth::id();

        $product = $this->productRepository->store($data);

        if($request->photos_ids){
            $this->storePhotos(explode(",",$request->photos_ids),$product->id);
        }

        $result = new ProductResource($product);

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("product created successfully.")
                    ->setCode(201)
                    ->setResult($result);
    }




    public function show($id)
    {
        $product = $this->productRepository->findById($id, ['photo', 'category', 'created_by', 'updated_by']);

        if (!$product) {
            throw new NotFoundHttpException();
        }

        $result =  new ProductResource($product);

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }


    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new NotFoundHttpException();
        }

        $data = $request->validated();

        // add rest of the values
        $data['updated_by_id'] = Auth::id();

        $product = $this->productRepository->updateByInstance($product, $data);

        // store photos products
        $this->storePhotos(explode(",",$request->photos_ids),$product->id);


        $result = new ProductResource($product);

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("product updated successfully.")
                    ->setCode(200)
                    ->setResult($result);
    }


    public function destroy($id)
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new NotFoundHttpException();
        }
        // delete photos for products
        $ids = $this->productPhotoRepository->pluckBy('product_id',$id,'id');
        $this->productPhotoRepository->destroyAll($ids);

        $this->productRepository->destroy($id);

        return $this->jsonResponse()->setStatus(true)
                    ->setMessage("product deleted successfully.")
                    ->setCode(200);
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->ids;

        $count = $this->productRepository->destroyAll($ids);

        return $this->jsonResponse()->setStatus(true)
            ->setMessage("$count products deleted successfully.")
            ->setCode(200);
    }


    protected function storePhotos($photos_ids,$product_id){

        $ids = $this->productPhotoRepository->pluckBy('product_id',$product_id,'id');

        if($photos_ids){
            $this->productPhotoRepository->destroyAll($ids);
            foreach ($photos_ids as $id) {
                $data['media_id'] = $id;
                $data['created_by_id'] = Auth::id();
                $data['product_id'] = $product_id;
                $this->productPhotoRepository->store($data);
            }
        }

    }

}
