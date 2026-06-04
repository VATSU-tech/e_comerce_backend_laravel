<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\ProductRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data): Product {
            $images = Arr::pull($data, 'images', []);
            $primaryImageIndex = Arr::pull($data, 'primary_image_index');

            $product = $this->productRepository->create($data);
            $this->storeImages($product, $images, is_numeric($primaryImageIndex) ? (int) $primaryImageIndex : null);

            return $this->productRepository->findOrFail($product->id);
        });
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data): Product {
            $images = Arr::pull($data, 'images', []);
            $primaryImageIndex = Arr::pull($data, 'primary_image_index');

            $this->productRepository->update($product, $data);
            $this->storeImages($product, $images, is_numeric($primaryImageIndex) ? (int) $primaryImageIndex : null);

            return $this->productRepository->findOrFail($product->id);
        });
    }

    /**
     * @param array<int, UploadedFile> $images
     */
    public function addImages(Product $product, array $images, ?int $primaryImageIndex = null): Product
    {
        return DB::transaction(function () use ($product, $images, $primaryImageIndex): Product {
            $this->storeImages($product, $images, $primaryImageIndex);

            return $this->productRepository->findOrFail($product->id);
        });
    }

    public function deleteImage(Product $product, ProductImage $image): Product
    {
        return DB::transaction(function () use ($product, $image): Product {
            $wasPrimary = $image->is_primary;

            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            if ($wasPrimary) {
                $replacement = $product->images()->oldest()->first();

                if ($replacement !== null) {
                    $this->setPrimaryImage($product, $replacement);
                }
            }

            return $this->productRepository->findOrFail($product->id);
        });
    }

    public function setPrimaryImage(Product $product, ProductImage $image): Product
    {
        return DB::transaction(function () use ($product, $image): Product {
            $product->images()->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);

            return $this->productRepository->findOrFail($product->id);
        });
    }

    /**
     * @param array<int, UploadedFile> $images
     */
    private function storeImages(Product $product, array $images, ?int $primaryImageIndex = null): void
    {
        if ($images === []) {
            return;
        }

        $images = array_values($images);
        $alreadyHasPrimary = $product->images()->where('is_primary', true)->exists();
        $primaryIndex = $primaryImageIndex !== null && array_key_exists($primaryImageIndex, $images)
            ? $primaryImageIndex
            : ($alreadyHasPrimary ? null : 0);

        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');
            $isPrimary = $primaryIndex === $index;

            if ($isPrimary) {
                $product->images()->update(['is_primary' => false]);
            }

            $product->images()->create([
                'image_path' => $path,
                'is_primary' => $isPrimary,
            ]);
        }
    }
}
