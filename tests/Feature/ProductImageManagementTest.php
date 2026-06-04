<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductImageManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product_with_multiple_images_and_first_image_becomes_primary(): void
    {
        Storage::fake('public');
        $admin = $this->adminUser();
        $category = $this->category();

        Sanctum::actingAs($admin);

        $response = $this->post('/api/v1/products', [
            'category_id' => $category->id,
            'name' => 'Camera',
            'slug' => 'camera',
            'price' => 199.99,
            'sku' => 'CAM-001',
            'images' => [
                UploadedFile::fake()->image('front.jpg'),
                UploadedFile::fake()->image('side.webp'),
            ],
        ], ['Accept' => 'application/json']);

        $response->assertCreated()
            ->assertJsonCount(2, 'images')
            ->assertJsonPath('images.0.is_primary', true)
            ->assertJsonPath('images.1.is_primary', false);

        $product = Product::query()->where('slug', 'camera')->firstOrFail();

        $this->assertDatabaseCount('product_images', 2);
        $this->assertTrue($product->images()->firstOrFail()->is_primary);

        ProductImage::query()->each(fn (ProductImage $image) => Storage::disk('public')->assertExists($image->image_path));
    }

    public function test_admin_can_select_primary_image_during_multiple_upload(): void
    {
        Storage::fake('public');
        $admin = $this->adminUser();
        $category = $this->category();

        Sanctum::actingAs($admin);

        $response = $this->post('/api/v1/products', [
            'category_id' => $category->id,
            'name' => 'Laptop',
            'slug' => 'laptop',
            'price' => 999.99,
            'sku' => 'LAP-001',
            'primary_image_index' => 1,
            'images' => [
                UploadedFile::fake()->image('closed.png'),
                UploadedFile::fake()->image('open.jpeg'),
            ],
        ], ['Accept' => 'application/json']);

        $response->assertCreated()
            ->assertJsonPath('images.0.is_primary', true)
            ->assertJsonPath('images.1.is_primary', false);

        $this->assertDatabaseHas('product_images', [
            'is_primary' => true,
        ]);
    }

    public function test_admin_can_add_images_to_existing_product(): void
    {
        Storage::fake('public');
        $admin = $this->adminUser();
        $product = $this->product();

        Sanctum::actingAs($admin);

        $this->post('/api/v1/products/'.$product->id.'/images', [
            'images' => [
                UploadedFile::fake()->image('front.jpg'),
                UploadedFile::fake()->image('back.png'),
            ],
        ], ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonCount(2, 'images')
            ->assertJsonPath('images.0.is_primary', true);

        $this->assertDatabaseCount('product_images', 2);
    }

    public function test_admin_can_delete_existing_image_and_primary_falls_back_to_next_image(): void
    {
        Storage::fake('public');
        $admin = $this->adminUser();
        $product = $this->product();
        $primary = $this->imageFor($product, true);
        $secondary = $this->imageFor($product, false);

        Sanctum::actingAs($admin);

        $this->deleteJson('/api/v1/products/'.$product->id.'/images/'.$primary->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('product_images', ['id' => $primary->id]);
        $this->assertDatabaseHas('product_images', ['id' => $secondary->id, 'is_primary' => true]);
        Storage::disk('public')->assertMissing($primary->image_path);
    }

    public function test_admin_can_change_primary_image(): void
    {
        Storage::fake('public');
        $admin = $this->adminUser();
        $product = $this->product();
        $primary = $this->imageFor($product, true);
        $secondary = $this->imageFor($product, false);

        Sanctum::actingAs($admin);

        $this->patchJson('/api/v1/products/'.$product->id.'/images/'.$secondary->id.'/primary')
            ->assertOk()
            ->assertJsonPath('primary_image.id', $secondary->id);

        $this->assertDatabaseHas('product_images', ['id' => $primary->id, 'is_primary' => false]);
        $this->assertDatabaseHas('product_images', ['id' => $secondary->id, 'is_primary' => true]);
    }

    public function test_product_image_upload_rejects_unsupported_extensions(): void
    {
        Storage::fake('public');
        $admin = $this->adminUser();
        $category = $this->category();

        Sanctum::actingAs($admin);

        $this->post('/api/v1/products', [
            'category_id' => $category->id,
            'name' => 'Invalid Image Product',
            'slug' => 'invalid-image-product',
            'price' => 19.99,
            'sku' => 'BAD-IMG-001',
            'images' => [UploadedFile::fake()->create('bad.gif', 10, 'image/gif')],
        ], ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('images.0');
    }

    public function test_product_image_upload_rejects_files_larger_than_five_megabytes(): void
    {
        Storage::fake('public');
        $admin = $this->adminUser();
        $category = $this->category();

        Sanctum::actingAs($admin);

        $this->post('/api/v1/products', [
            'category_id' => $category->id,
            'name' => 'Huge Image Product',
            'slug' => 'huge-image-product',
            'price' => 29.99,
            'sku' => 'HUGE-IMG-001',
            'images' => [UploadedFile::fake()->image('huge.jpg')->size(5121)],
        ], ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('images.0');
    }

    public function test_image_mutation_endpoints_require_authentication(): void
    {
        Storage::fake('public');
        $product = $this->product();
        $image = $this->imageFor($product, true);

        $this->post('/api/v1/products/'.$product->id.'/images', [
            'images' => [UploadedFile::fake()->image('front.jpg')],
        ], ['Accept' => 'application/json'])->assertUnauthorized();

        $this->deleteJson('/api/v1/products/'.$product->id.'/images/'.$image->id)->assertUnauthorized();
        $this->patchJson('/api/v1/products/'.$product->id.'/images/'.$image->id.'/primary')->assertUnauthorized();
    }

    private function adminUser(): User
    {
        $this->seed([RoleSeeder::class, PermissionSeeder::class, AdminUserSeeder::class]);

        return User::query()->where('email', User::ADMIN_EMAIL)->firstOrFail();
    }

    private function category(): Category
    {
        return Category::query()->create(['name' => 'Electronics', 'slug' => uniqid('electronics-')]);
    }

    private function product(): Product
    {
        $category = $this->category();

        return Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Phone',
            'slug' => uniqid('phone-'),
            'price' => 499.99,
            'sku' => uniqid('PHONE-'),
        ]);
    }

    private function imageFor(Product $product, bool $isPrimary): ProductImage
    {
        $path = UploadedFile::fake()->image(uniqid('product-').'.jpg')->store('products', 'public');

        return $product->images()->create([
            'image_path' => $path,
            'is_primary' => $isPrimary,
        ]);
    }
}
