<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->orderService->listOrders((int) $request->user()->id));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['total' => ['required', 'numeric', 'min:0']]);

        return response()->json($this->orderService->createOrder((int) $request->user()->id, (float) $data['total']), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        return response()->json($this->orderService->getOrder($id, (int) $request->user()->id));
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        return response()->json($this->orderService->cancelOrder($id, (int) $request->user()->id));
    }
}
