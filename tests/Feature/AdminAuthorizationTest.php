<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_seed_creates_super_admin_with_all_permissions(): void
    {
        $this->seed([RoleSeeder::class, PermissionSeeder::class, AdminUserSeeder::class]);

        $admin = User::query()->where('email', User::ADMIN_EMAIL)->firstOrFail();

        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($admin->hasPermissionTo('products.create'));
        $this->assertTrue($admin->hasPermissionTo('categories.delete'));
        $this->assertTrue($admin->hasPermissionTo('admin.access'));
    }

    public function test_login_response_returns_roles_and_permissions(): void
    {
        $this->seed([RoleSeeder::class, PermissionSeeder::class, AdminUserSeeder::class]);

        $this->postJson('/api/v1/auth/login', [
            'email' => User::ADMIN_EMAIL,
            'password' => 'password123',
            'device_name' => 'feature-test',
        ])
            ->assertOk()
            ->assertJsonPath('data.user.role', 'admin')
            ->assertJsonPath('data.user.roles.0.slug', 'admin')
            ->assertJsonFragment(['slug' => 'products.create'])
            ->assertJsonFragment(['slug' => 'admin.access']);
    }

    public function test_admin_can_create_update_and_delete_products(): void
    {
        $this->seed([RoleSeeder::class, PermissionSeeder::class, AdminUserSeeder::class]);

        $admin = User::query()->where('email', User::ADMIN_EMAIL)->firstOrFail();
        $category = Category::query()->create(['name' => 'Shoes', 'slug' => 'shoes']);

        Sanctum::actingAs($admin);

        $productId = $this->postJson('/api/v1/products', [
            'category_id' => $category->id,
            'name' => 'Running Shoe',
            'slug' => 'running-shoe',
            'price' => 49.99,
            'sku' => 'RUN-001',
        ])
            ->assertCreated()
            ->json('id');

        $this->putJson('/api/v1/products/'.$productId, [
            'name' => 'Trail Running Shoe',
        ])->assertOk()->assertJsonPath('name', 'Trail Running Shoe');

        $this->deleteJson('/api/v1/products/'.$productId)->assertNoContent();
    }

    public function test_customer_cannot_manage_products_or_admin_routes(): void
    {
        $this->seed([RoleSeeder::class, PermissionSeeder::class]);

        $customerRole = Role::query()->where('slug', 'customer')->firstOrFail();
        $customer = User::factory()->create();
        $customer->roles()->syncWithoutDetaching([$customerRole->id]);

        $category = Category::query()->create(['name' => 'Shoes', 'slug' => 'shoes']);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Running Shoe',
            'slug' => 'running-shoe',
            'price' => 49.99,
            'sku' => 'RUN-001',
        ]);

        Sanctum::actingAs($customer);

        $this->postJson('/api/v1/products', [
            'category_id' => $category->id,
            'name' => 'Walking Shoe',
            'slug' => 'walking-shoe',
            'price' => 39.99,
            'sku' => 'WLK-001',
        ])->assertForbidden();

        $this->putJson('/api/v1/products/'.$product->id, [
            'name' => 'Customer Edited Shoe',
        ])->assertForbidden();

        $this->deleteJson('/api/v1/products/'.$product->id)->assertForbidden();

        $this->getJson('/api/v1/admin/dashboard')->assertForbidden();
    }

    public function test_admin_only_category_mutation_routes(): void
    {
        $this->seed([RoleSeeder::class, PermissionSeeder::class, AdminUserSeeder::class]);

        $admin = User::query()->where('email', User::ADMIN_EMAIL)->firstOrFail();

        Sanctum::actingAs($admin);

        $categoryId = $this->postJson('/api/v1/categories', [
            'name' => 'Accessories',
            'slug' => 'accessories',
        ])
            ->assertCreated()
            ->json('id');

        $this->putJson('/api/v1/categories/'.$categoryId, [
            'name' => 'Premium Accessories',
        ])->assertOk()->assertJsonPath('name', 'Premium Accessories');

        $this->deleteJson('/api/v1/categories/'.$categoryId)->assertNoContent();
    }
}
