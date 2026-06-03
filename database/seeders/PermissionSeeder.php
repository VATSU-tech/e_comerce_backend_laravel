<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'products.create',
            'products.update',
            'products.delete',
            'categories.create',
            'categories.update',
            'categories.delete',
            'orders.manage',
            'payments.manage',
            'users.manage',
            'admin.access',
            'protected-routes.access',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate(
                ['slug' => $permission],
                [
                    'name' => Str::headline(str_replace('.', ' ', $permission)),
                    'slug' => $permission,
                ]
            );
        }

        $adminRole = Role::query()->where('slug', 'admin')->first();

        if ($adminRole !== null) {
            $adminRole->permissions()->sync(Permission::query()->pluck('id'));
        }
    }
}
