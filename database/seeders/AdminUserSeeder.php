<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::query()->firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin']
        );

        $admin = User::query()->updateOrCreate(
            ['email' => User::ADMIN_EMAIL],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
            ]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
