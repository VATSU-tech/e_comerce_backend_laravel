<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage_users',
            'manage_products',
            'manage_orders',
            'manage_inventory',
            'view_orders',
            'create_orders',
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['slug' => $perm],
                [
                    'name' => ucfirst(str_replace('_', ' ', $perm)),
                    'slug' => $perm,
                ]
            );
        }
    }
}
