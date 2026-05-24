<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function initiate(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        return response()->json($this->paymentService->initiate((int) $payload['order_id'], (int) $payload['payment_method_id'], (float) $payload['amount']), 201);
    }

    public function confirm(Request $request): JsonResponse
    {
        $payload = $request->validate(['transaction_reference' => ['required', 'string']]);

        return response()->json($this->paymentService->confirm($payload['transaction_reference']));
    }

    public function webhook(Request $request): JsonResponse
    {
        return response()->json($this->paymentService->webhook($request->all()));
    }
}
