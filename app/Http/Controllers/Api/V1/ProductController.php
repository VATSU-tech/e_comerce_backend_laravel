<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Products\DeleteProductImageRequest;
use App\Http\Requests\Api\V1\Products\SetPrimaryProductImageRequest;
use App\Http\Requests\Api\V1\Products\StoreProductImagesRequest;
use App\Http\Requests\Api\V1\Products\StoreProductRequest;
use App\Http\Requests\Api\V1\Products\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductService $productService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $products = $this->productRepository->paginate($request->only(['category_id', 'q']), (int) $request->integer('limit', 20));

        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());

        return response()->json($product, 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($this->productRepository->findOrFail($product->id));
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $updated = $this->productService->update($product, $request->validated());

        return response()->json($updated);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $this->productRepository->delete($product);

        return response()->json([], 204);
    }

    public function storeImages(StoreProductImagesRequest $request, Product $product): JsonResponse
    {
        $validated = $request->validated();
        $updated = $this->productService->addImages(
            $product,
            $validated['images'],
            isset($validated['primary_image_index']) ? (int) $validated['primary_image_index'] : null,
        );

        return response()->json($updated, 201);
    }

    public function destroyImage(DeleteProductImageRequest $request, Product $product, ProductImage $image): JsonResponse
    {
        $this->productService->deleteImage($product, $image);

        return response()->json([], 204);
    }

    public function setPrimaryImage(SetPrimaryProductImageRequest $request, Product $product, ProductImage $image): JsonResponse
    {
        return response()->json($this->productService->setPrimaryImage($product, $image));
    }
}
