<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin') && $user->hasPermissionTo('products.create');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasRole('admin') && $user->hasPermissionTo('products.update');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole('admin') && $user->hasPermissionTo('products.delete');
    }
}
