<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::query()->with('children')->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Category::class);

        $category = Category::query()->create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]));

        return response()->json($category, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Category::query()->with(['parent', 'children'])->findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::query()->findOrFail($id);

        $this->authorize('update', $category);

        $category->update($request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:categories,slug,'.$id],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]));

        return response()->json($category->refresh());
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::query()->findOrFail($id);

        $this->authorize('delete', $category);

        $category->delete();

        return response()->json([], 204);
    }
}
