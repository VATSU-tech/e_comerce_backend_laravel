<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $products = $this->productRepository->paginate($request->only(['category_id', 'q']), (int) $request->integer('limit', 20));

        return response()->json($products);
    }

    public function store(Request $request): JsonResponse
    {
        $product = $this->productRepository->create($request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string'],
            'slug' => ['required', 'string', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sku' => ['required', 'string', 'unique:products,sku'],
            'is_active' => ['sometimes', 'boolean'],
        ]));

        return response()->json($product, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->productRepository->findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);
        $updated = $this->productRepository->update($product, $request->validate([
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'name' => ['sometimes', 'string'],
            'slug' => ['sometimes', 'string', 'unique:products,slug,'.$id],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'sku' => ['sometimes', 'string', 'unique:products,sku,'.$id],
            'is_active' => ['sometimes', 'boolean'],
        ]));

        return response()->json($updated);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);
        $this->productRepository->delete($product);

        return response()->json([], 204);
    }
}
