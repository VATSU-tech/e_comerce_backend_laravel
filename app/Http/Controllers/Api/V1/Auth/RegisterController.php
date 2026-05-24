<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, AuthService $authService): JsonResponse
    {
        $credentials = $request->safe()->only(['name', 'email', 'password']);
        $result = $authService->register($credentials, $request->input('device_name'));

        $user = $result['user']->load(['roles.permissions', 'permissions']);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data' => [
                'token' => $result['token'],
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
            ],
        ], 201);
    }
}
