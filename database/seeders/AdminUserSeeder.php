<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();

        if (! $adminRole) {
            $this->command->warn('Admin role not found. Run RoleSeeder before AdminUserSeeder.');
            return;
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@ecommerce.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
            ]
        );

        if (! $admin->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole->id);
        }
    }
}
