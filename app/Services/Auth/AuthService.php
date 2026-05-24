<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * @param array{name:string,email:string,password:string} $payload
     * @return array{user:User,token:string}
     */
    public function register(array $payload, ?string $deviceName = null): array
    {
        $user = User::query()->create($payload);

        $customerRole = Role::query()
            ->where('slug', 'customer')
            ->orWhere('name', 'customer')
            ->first();

        if ($customerRole !== null) {
            $user->roles()->syncWithoutDetaching([$customerRole->id]);
        }

        $token = $user->createToken($this->resolveDeviceName($deviceName))->plainTextToken;

        return compact('user', 'token');
    }

    /**
     * @return array{user:User,token:string}
     */
    public function login(string $email, string $password, ?string $deviceName = null): array
    {
        $user = User::query()->where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        $token = $user->createToken($this->resolveDeviceName($deviceName))->plainTextToken;

        return compact('user', 'token');
    }

    private function resolveDeviceName(?string $deviceName): string
    {
        return filled($deviceName) ? $deviceName : 'web';
    }
}
