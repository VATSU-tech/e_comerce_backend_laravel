<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return Product::query()
            ->when(isset($filters['category_id']), fn ($query) => $query->where('category_id', $filters['category_id']))
            ->when(isset($filters['q']), fn ($query) => $query->where('name', 'like', '%'.$filters['q'].'%'))
            ->latest()
            ->paginate(min($perPage, 100));
    }

    public function findOrFail(int $id): Product
    {
        return Product::query()->with(['category', 'images', 'variants'])->findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::query()->create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
