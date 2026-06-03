<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin') && $user->hasPermissionTo('categories.create');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasRole('admin') && $user->hasPermissionTo('categories.update');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasRole('admin') && $user->hasPermissionTo('categories.delete');
    }
}
