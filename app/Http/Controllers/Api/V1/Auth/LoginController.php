<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, AuthService $authService): JsonResponse
    {
        try {
            $result = $authService->login(
                (string) $request->input('email'),
                (string) $request->input('password'),
                $request->input('device_name')
            );
        } catch (AuthenticationException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
                'errors' => [
                    'email' => ['The provided credentials are incorrect.'],
                ],
            ], 422);
        }

        $user = $result['user']->load(['roles.permissions', 'permissions']);

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'token' => $result['token'],
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
            ],
        ]);
    }
}
