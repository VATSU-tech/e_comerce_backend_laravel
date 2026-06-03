<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Policies\CategoryPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);

        Gate::before(fn ($user) => $user->hasRole('admin') ? true : null);

        RateLimiter::for('login', function (Request $request): Limit {
            $email = (string) $request->input('email');

            return Limit::perMinute(5)->by($email.$request->ip());
        });
    }
}
