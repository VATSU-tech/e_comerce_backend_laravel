<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());

        $customerRole = Role::query()->where('slug', 'customer')->orWhere('name', 'customer')->first();

        if ($customerRole !== null) {
            $user->roles()->syncWithoutDetaching([$customerRole->id]);
        }

        $token = $user->createToken($request->input('device_name', 'web'))->plainTextToken;

        $user->load(['roles.permissions', 'permissions']);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
            ],
        ], 201);
    }
}
