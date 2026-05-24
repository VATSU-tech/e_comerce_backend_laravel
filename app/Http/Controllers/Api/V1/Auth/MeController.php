<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user()->load(['roles.permissions', 'permissions']);

        return response()->json([
            'success' => true,
            'message' => 'Authenticated user profile.',
            'data' => [
                'user' => new UserResource($user),
            ],
        ]);
    }
}
