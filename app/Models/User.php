<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ADMIN_EMAIL = 'admin@ecommerce.com';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        static::saved(function (User $user): void {
            $user->assignAdminRoleWhenEligible();
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where(fn ($query) => $query->where('slug', $role)->orWhere('name', $role))
            ->exists();
    }

    public function hasPermissionTo(string $permission): bool
    {
        return $this->permissions()
            ->where(fn ($query) => $query->where('slug', $permission)->orWhere('name', $permission))
            ->exists()
            || $this->roles()
                ->whereHas('permissions', fn ($query) => $query->where('slug', $permission)->orWhere('name', $permission))
                ->exists();
    }

    /**
     * @return Collection<int, Permission>
     */
    public function allPermissions(): Collection
    {
        $this->loadMissing(['roles.permissions', 'permissions']);

        return $this->permissions
            ->merge($this->roles->flatMap(fn (Role $role) => $role->permissions))
            ->unique('id')
            ->values();
    }

    public function assignAdminRoleWhenEligible(): void
    {
        if ($this->email !== self::ADMIN_EMAIL || ! Schema::hasTable('roles')) {
            return;
        }

        $adminRole = Role::query()->where('slug', 'admin')->first();

        if ($adminRole !== null && ! $this->roles()->whereKey($adminRole->id)->exists()) {
            $this->roles()->syncWithoutDetaching([$adminRole->id]);
        }
    }
}
