<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\OrderStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartProductFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_simple_product_to_cart_immediately_after_creation(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $product = $this->product(price: 25.50);

        $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])
            ->assertCreated()
            ->assertJsonPath('product_id', $product->id)
            ->assertJsonPath('quantity', 2)
            ->assertJsonPath('product.id', $product->id);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_user_can_update_product_quantity_in_cart(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $product = $this->product();
        $itemId = $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->json('id');

        $this->putJson('/api/v1/cart/items/'.$itemId, [
            'quantity' => 4,
        ])
            ->assertOk()
            ->assertJsonPath('product_id', $product->id)
            ->assertJsonPath('quantity', 4);

        $this->assertDatabaseHas('cart_items', [
            'id' => $itemId,
            'product_id' => $product->id,
            'quantity' => 4,
        ]);
    }

    public function test_user_can_remove_product_from_cart(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $product = $this->product();
        $itemId = $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->json('id');

        $this->deleteJson('/api/v1/cart/items/'.$itemId)->assertNoContent();

        $this->assertDatabaseMissing('cart_items', ['id' => $itemId]);
    }

    public function test_user_can_retrieve_cart_with_product_items_and_product_based_total(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $firstProduct = $this->product(price: 10.00, sku: 'SKU-ONE');
        $secondProduct = $this->product(price: 15.25, sku: 'SKU-TWO');

        $this->postJson('/api/v1/cart/items', [
            'product_id' => $firstProduct->id,
            'quantity' => 2,
        ])->assertCreated();
        $this->postJson('/api/v1/cart/items', [
            'product_id' => $secondProduct->id,
            'quantity' => 3,
        ])->assertCreated();

        $this->getJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonCount(2, 'items')
            ->assertJsonPath('items.0.product_id', $firstProduct->id)
            ->assertJsonPath('items.0.product.id', $firstProduct->id)
            ->assertJsonPath('total', 65.75);
    }

    public function test_user_can_create_order_from_product_based_cart(): void
    {
        $this->seed(OrderStatusSeeder::class);
        Sanctum::actingAs(User::factory()->create());
        $product = $this->product(price: 19.99);

        $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 3,
        ])->assertCreated();

        $this->postJson('/api/v1/orders')
            ->assertCreated()
            ->assertJsonPath('total', '59.97')
            ->assertJsonPath('items.0.product_id', $product->id)
            ->assertJsonPath('items.0.price', '19.99')
            ->assertJsonPath('items.0.quantity', 3)
            ->assertJsonPath('items.0.product.id', $product->id);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
        $this->assertDatabaseCount('cart_items', 0);
    }

    private function product(float $price = 12.99, string $sku = 'SKU-TEST'): Product
    {
        $category = Category::query()->firstOrCreate([
            'slug' => 'electronics',
        ], [
            'name' => 'Electronics',
        ]);

        return Product::query()->create([
            'category_id' => $category->id,
            'name' => uniqid('Product '),
            'slug' => uniqid('product-'),
            'description' => 'Simple product without extra configuration.',
            'price' => $price,
            'sku' => uniqid($sku.'-'),
        ]);
    }
}
